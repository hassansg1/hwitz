<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\EmailAttachment;
use App\Models\EmailUser;
use App\Models\User;
use App\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessagesController extends Controller
{
    public function __construct()
    {
        DB::statement('SET sql_mode=(SELECT REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY",""))');
    }

    public function index(Request $request, $type = 'inbox')
    {
        $data = [];
        if ($type == 'inbox') {
            $data = $this->getInboxMessages($request , 0);
        }elseif($type == 'archived'){
            $data = $this->getInboxMessages($request , 1);
        }

        return response()->json(['data' => $data]);
    }

    public function getInboxMessages($request , $archived)
    {
        DB::statement('SET sql_mode=(SELECT REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY",""))');

        $subquery = DB::table('emails_users')
            ->select(DB::raw('MAX(id) as lastid'))
            ->where(function($q){
                $q->where('emails_users.user_id', Auth::id())
                ->orWhere('emails_users.creator_id', Auth::id());
            })
            ->where('emails_users.is_archived',$archived)
            ->groupBy(DB::raw('CONCAT(LEAST(emails_users.user_id, emails_users.creator_id), ".", GREATEST(emails_users.user_id, emails_users.creator_id))'));

        $messages = EmailUser::with(['creator','user'])->joinSub($subquery, 'conversations', function ($join) {
                $join->on('emails_users.id', '=', 'conversations.lastid');
            })
            ->where(function ($query) {
                $query->where('emails_users.creator_id', '!=', Auth::id())
                    ->orWhere('emails_users.user_id', '!=', Auth::id());
            })
            ->where('emails_users.is_archived',$archived) // Added later
            ->orderBy('emails_users.created', 'DESC')
            ->get();
            
        $userIdArray = [];
        $data = [];
        foreach ($messages as $key => $message) {
            $userId = null;
            if ($message->user_id == 1073) {
                $userId = $message->creator_id;
            } else {
                $userId = $message->user_id;
            }
            if (!in_array($userId, $userIdArray)){
                $userIdArray[] = $userId;
                $data[] = $message;
            }
        }
        
        return $data;
    }

    public function getChatDetail(Request $request, $id)
    {
        DB::statement('SET sql_mode=(SELECT REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY",""))');
        $archived = $request->archived ?? 0;
        $messages = Email::with('attachments')->select('emails.body', 'emails_users.id', 'users.firstname', 'users.lastname', 'emails_users.created', 'users.profile_picture', 'emails_users.creator_id as messaged_by', 'emails_users.user_id','emails.id','emails_users.id as email_users_id' , 'emails_users.is_archived')
            ->join('emails_users', function ($q) use ($archived) {
                $q->on('emails_users.message_id', '=', 'emails.id');
                $q
                    // ->where('emails_users.user_id',Auth::id())
                    // ->where(function($q) use($id) {
                    //     $q->where(['emails_users.creator_id' => $id , 'emails_users.user_id' =>  Auth::id()]) -> orWhere(['emails_users.creator_id' => Auth::id() , 'emails_users.user_id' =>  $id]);
                    // })
                    ->where('emails_users.is_archived', $archived);
            })
            ->join('users', function ($q) {
                $q->on('emails_users.creator_id', '=', 'users.id');
            })
            ->where(function ($query) use ($id) {
                $query->where(function ($subquery) use ($id) {
                    $subquery->where('emails_users.creator_id', $id)
                        ->where('emails_users.user_id', Auth::id());
                })->orWhere(function ($subquery) use ($id) {
                    $subquery->where('emails_users.creator_id', Auth::id())
                        ->where('emails_users.user_id', $id);
                });
            })
            // ->where('emails_users.sent', 0) // Removed later
            ->where('emails_users.is_archived', $archived)
            ->orderBy('emails_users.created', 'asc')->groupBy('emails.id')->get()
            ->groupBy('created');
        return $messages;
    }

    public function loadUsers(Request $request)
    {

        // DB::statement('SET sql_mode=(SELECT REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY",""))');
        $auth_id = Auth::id();
        $building_id = session('building_id') ?? 159;
        $users = User::
        select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'users.usertype_id', 'buildings_users.building_id as buildings_users_building_id', 'units.building_id as unit_building_id', 'users.mobile', 'units.unit_no')
            ->leftJoin('units_users', 'units_users.user_id', '=', 'users.id')
            ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
            ->leftJoin('buildings_users', 'buildings_users.user_id', '=', 'users.id')
            ->where('users.id', '!=', $auth_id)
            ->where(function ($subquery) use ($building_id) {
                $subquery->where('units.building_id', $building_id)
                    ->orWhere('buildings_users.building_id', $building_id)
                    ->orWhere('users.usertype_id', 1);
            })
            ->groupBy('users.id')->get();
        $user_types = UserTypes::where('status', 1)->pluck('name', 'id')->toArray();
        $list = array();
        foreach ($users as $key => $value) {
            if (isset($user_types[$value['usertype_id']])) {
                $list[$key]['id'] = $value['id'];
                $list[$key]['name'] = $value['firstname'] . " " . $value['lastname'] . " - " . $value['email'] . " (" . $user_types[$value['usertype_id']] . ")";
            }

        }
        return $list;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'users' => 'required',
            // 'message' => 'required'
        ]);
        $files = $this->upload($request);

        $email = Email::create([
            'subject' => $request->message,
            'body' => $request->message,
            'creator_id' => Auth::id(),
            'created' => Carbon::now(),
            'modified' => Carbon::now()
        ]);
        foreach($files as $file){
            EmailAttachment::create([
                'message_id' => $email->id,
                'attachment_name' => $file,
                'old_name' => $file,
                'created' => Carbon::now(),
                'modified' => Carbon::now()
            ]);
        }
        foreach ($request->users as $user_id) {
            EmailUser::create([
                'message_id' => $email->id,
                'user_id' => $user_id,
                'creator_id' => Auth::id(),
                'label' => 'unread',
                'sent' => 0,
                'is_archived' => 0,
                'is_acknowledged' => 0,
                'created' => Carbon::now(),
                'modified' => Carbon::now(),
                'modifier_id' => Auth::id()
            ]);
        }

        //send email

    }

    public function upload(Request $request)
    {
        $file_path = [];
        if ($request->hasFile('attachment')) {
            $files = $request->file('attachment');
            foreach($files as $file){
                $uniqueName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('uploads', $uniqueName);
                $file_path[] = $filePath;
            }
        }
        return $file_path;
    }

    public function archiveMessage(Request $request, $id, $value){
        EmailUser::where('id',$id)->update(['is_archived' => $value]);
        return response()->json(['message' => 'Message archived successfully.']);
    }

}
