<?php

namespace App\Http\Controllers;

use App\Models\BroadcastMessage;
use App\Models\BroadcastMessageUser;
use App\Models\BroadcastTemplate;
use App\Models\Building;
use App\Models\User;
use App\Models\VerificationSequence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Symfony\Component\HttpFoundation\RequestStack;

class BroadcastController extends Controller
{
    public function broadcastHistory(Request $request){
        $results = BroadcastMessage::with('verification')
                        ->select("broadcast_messages.*")
                        ->selectRaw( "group_concat(broadcast_messages_users.user_id) as user_id")
                        ->join('broadcast_messages_users','broadcast_messages_users.message_id','=','broadcast_messages.first_message_id')
                        ->where('broadcast_messages.creator_id',Auth::id())
                        ->where('broadcast_messages.building_id',$request->building_id)
                        ->orderBy('broadcast_messages.id','desc')
                        ->groupBy('broadcast_messages.id');

        $perPage = $request->totalItems ??  getEntriesPerPage();
        $currentPage = (int)$request->current_page;

        $total = $results->getQuery()->getCountForPagination();
        $data = $results->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $data;
        return response()->json($returnObj);

    }

    public function stopVerificationBroadcast(Request $request, $id){
        $users = BroadcastMessageUser::select('user_id')->where('message_id',$id)->pluck('user_id')->toArray();
        if(count($users) > 0){
            $counter = VerificationSequence::where('message_id',$id)->count();
            VerificationSequence::where('message_id',$id)->update(
                [
                    'status' => 'cancelled',
                    'action_date' => "'".date('Y-m-d H:i:s')."'",
                    'action_by' => Auth::id()
                ]
            );
            if($counter) return response()->json(['message' => 'The verification has been stopped.']);
            else return response()->json(['message' => 'The verification sequence is already stopped.']);
        }else{
            return response()->json(['message' => 'The verification sequence has no users.']);
        }
        
        
    }

    public function getUserPopupDetails(Request $request, $id, $user_dtl){
        // $broadcastmsg_dtl = $this->BroadcastMessages->find('first', array('conditions' => array('BroadcastMessages.id' => $id)));

        $data = BroadcastMessage::find($id);
        $description = $data['description'];
        $creator_id = $data['creator_id'];
        $message_type = $data['message_type'];
        $services = $data['services'];
        $created_msg_date = $data['created'];
        $attachment_name = $data['attachment_name'];
        $user_type = array(1 => 'Admin', 15 => 'Landlord', '12' => 'Manager', '13' => 'Resident', '22' => 'Agent', '31' => 'Agent Contractor', '30' => 'Maintenance', '23' => 'ServiceProvider');

        //Getting From Name
        // $from_userlist = $this->User->find('first', array('conditions' => array('User.id' => $creator_id), 'fields' => array('User.firstname', 'User.lastname', 'User.usertype_id')));
        $from_userlist = User::select('firstname','lastname','usertype_id')->where('id',$creator_id)->first();
        $from_name = $from_userlist['firstname'] . " " . $from_userlist['lastname'];
        $usertype_id = $from_userlist['usertype_id'];

        //Getting To Name
        // $broadcast_sentmsg_dtl = $this->User->find('all', array('conditions' => array('User.id' => explode(",", $user_dtl)), 'fields' => array('User.firstname', 'User.lastname', 'User.usertype_id','User.email','User.mobile')));
        $broadcast_sentmsg_dtl = User::select('firstname','lastname','usertype_id','email','mobile')->whereIn('id',explode(",",$user_dtl))->get();

        $data = array();
        foreach ($broadcast_sentmsg_dtl as $val) {
            $recv_email = $val['email'];
            $recv_mobile = $val['mobile'];
            $detail =  $message_type == 'email' ? $recv_email : $recv_mobile;
            $recv_name = $val['firstname'] . " " . $val['lastname']." [".$detail."]";
            $usertype_name = $user_type[$val['usertype_id']];
            $data[$recv_name] = $usertype_name;

        }
        $usertype_name = $user_type[$usertype_id];
        
        $str = "";
        $str.="<div><span><strong>Message Type: </strong></span><span>" . $message_type ." </span></div>";
        if (!is_null($services)) {
            $str.="<div><span><strong>Selected Services: </strong></span><span>" . $services ." </span></div>";
        }
        $str.="<div style='width:100%;float:left;'><span style='float: left; width: 100%;'><strong>Description : </strong></span><div>" . $description . "</div></div>";
        $str.="<div><span><strong>From : </strong></span><span>" . $from_name . "</span><span> (" . $usertype_name . " User)</span></div>";
        $str.="<div><span><strong>To: </strong></span>";
        $user_to = '';
        foreach ($data as $key => $val) {
            $user_to.="<span>" . $val . "</span><span>( " . $key . " )</span>&nbsp;<br />";
        }
        $str.=trim($user_to, ",") . "</div>";
        // if ($message_type == "mms") {
        //     $attachment_url = Configure::read('file_path.attachment_path');
        //     $file_name = Router::url('/', true) . $attachment_url . $attachment_name;
        //     $str.="<div><span><strong>Attachment :</strong></span><span><a href='" . $file_name . "' download='broadcast_mms'>Download here</a></span><br></div>";
        // }

        return response()->json(['html' => $str]);
    }

    public function getResidentsByFilters(Request $request){
        // dd($request->all());
        $sconditions2 = [];
        $received_data = $request->all();
        $building_id = $received_data['building_id'];
        $owner = Building::where('id', $building_id)->first();
        $owner_user = User::find($owner->user_id);
        
        if ((isset($received_data['tv']) && $received_data['tv'] == 1) 
        || (isset($received_data['phone']) && $received_data['phone'] == 1)
        || (isset($received_data['internet']) && $received_data['internet'] == 1)
        || (isset($received_data['parking']) && $received_data['parking'] == 1)) {


            $users_data1=[];

        }else{
            $users_data1 = User::select('users.*','units.unit_no')
                            ->join('buildings_users','buildings_users.user_id','=','users.id')
                            ->leftJoin('units_users','units_users.user_id','=','users.id')
                            ->join('units','units.id','=','units_users.unit_id')
                            ->when(isset($received_data['usertype_id']) && $received_data['usertype_id'] != 'all', function($q) use($received_data) {
                                $q->where('users.usertype_id',$received_data['usertype_id']);
                            })
                            ->when(isset($received_data['template_type']) && $received_data['template_type'] == 'v' && $received_data['unverified'], function ($q){
                                $q->where(function($q){
                                    $q->where('users.email_verified',0)->orWhere('users.mobile_verification',"No");
                                });
                            })
                            ->where('buildings_users.building_id',$building_id)
                            ->orderBy('units.unit_no','asc')
                            ->orderBy('users.firstname','asc')
                            ->orderBy('users.lastname','asc')
                            ->groupBy('users.id')->get()->toArray();
        }

        $users_data2 = User::select('users.*','units.unit_no')
                        ->leftJoin('units_users','units_users.user_id','=','users.id')
                        ->join('units','units.id','=','units_users.unit_id')

                        // ->when((isset($received_data['tv']) && $received_data['tv'] == 1) 
                        //         || (isset($received_data['phone']) && $received_data['phone'] == 1)
                        //         || (isset($received_data['internet']) && $received_data['internet'] == 1)
                        //         || (isset($received_data['parking']) && $received_data['parking'] == 1),function($q){
                        //     $q->when(isset($received_data['tv']) && $received_data['tv'] == 1,function($subquery){
                        //         $subquery->orWhere('units.cable',1);
                        //     });
                        //     $q->when(isset($received_data['phone']) && $received_data['phone'] == 1,function($subquery){
                        //         $subquery->orWhere('units.telephone',1);
                        //     });
                        //     $q->when(isset($received_data['internet']) && $received_data['internet'] == 1,function($subquery){
                        //         $subquery->orWhere('units.is_internet',1);
                        //     });
                        //     $q->when(isset($received_data['parking']) && $received_data['parking'] == 1,function($subquery){
                                // $subquery->whereIn('User.id', function ($subquery2) {
                                //     $subquery2->select('user_id')
                                //         ->from('units_users')
                                //         ->join('units', 'units.id', '=', 'units_users.unit_id')
                                //         ->where('unittype_id', 10);
                                // });
                        //     });
                        // })
                        ->when(isset($received_data['usertype_id']) && $received_data['usertype_id'] == 15, function($q){
                            $q->whereIn('users.usertype_id', array(24,31,25,30,12));
                        })
                        ->when(isset($received_data['usertype_id']) && $received_data['usertype_id'] != 'all' , function($q) use($received_data){
                            $q->where('users.usertype_id',$received_data['usertype_id']);
                        })
                        ->when(isset($received_data['template_type']) && $received_data['template_type'] == 'v' && $received_data['unverified'], function($q){
                            $q->where(function($q){
                                $q->where('users.email_verified',0)->orWhere('users.mobile_verification',"No");
                            });
                        })
                        ->where('units.building_id',$building_id);

        if((isset($received_data['tv']) && $received_data['tv'] == 1) 
                || (isset($received_data['phone']) && $received_data['phone'] == 1)
                || (isset($received_data['internet']) && $received_data['internet'] == 1)
                || (isset($received_data['parking']) && $received_data['parking'] == 1)){

                    $users_data2 = $users_data2->where(function($q) use ($received_data){
                        if(isset($received_data['tv']) && $received_data['tv'] == 1){
                            // dd('asds');
                            $q->orWhere('units.cable',1);
                        }
                        if(isset($received_data['phone']) && $received_data['phone'] == 1){
                            $q->orWhere('units.telephone',1);
                        }
                        if(isset($received_data['internet']) && $received_data['internet'] == 1){
                            $q->orWhere('units.is_internet',1);
                        }
                        if(isset($received_data['parking']) && $received_data['parking'] == 1){
                            $q->orWhereIn('users.id', function ($subquery2) {
                                $subquery2->select('user_id')
                                    ->from('units_users')
                                    ->join('units', 'units.id', '=', 'units_users.unit_id')
                                    ->where('unittype_id', 10);
                            });
                        }
                    });
        }
        $users_data2 = $users_data2->orderBy('units.unit_no','asc')
                        ->orderBy('users.firstname','asc')
                        ->orderBy('users.lastname','asc')
                        ->groupBy('users.id')
                        // ->toSql();
                        // dd($users_data2);
                        ->get()->toArray();
        if (isset($received_data['usertype_id']) && $received_data['usertype_id'] != 'all'){
            // remove duplicates
            $users_data = array_unique(array_merge($users_data1, $users_data2), SORT_REGULAR);
            asort($users_data);
        } else {
            $users_data = $users_data2;
        }

        $users['landlord'][] = array('id' => $owner_user['id'], 'name' => trim(ucwords($owner_user['firstname'] . " " . $owner_user['lastname'])));

        if (!empty($users_data)) {
            foreach ($users_data as $users_info) {
                $id = $users_info['id'];
                $first_name = !empty($users_info['firstname']) ? $users_info['firstname'] : "";
                $last_name = !empty($users_info['lastname']) ? $users_info['lastname'] : "";
                $unit_no = isset($users_info['unit_no'])? $users_info['unit_no'] :'';

                if (isset($received_data['usertype_id']) && $received_data['usertype_id'] != 'all'){
                    if ($users_info['usertype_id'] == 13)
                        $users['resident'][] = array('id' => $id, 'unit' => $unit_no, 'name'=> trim(ucwords($first_name . " " . $last_name)) , 'mobile_verification' => $users_info['mobile_verification'] , 'email_verified' => $users_info['email_verified']); 
                    else if (in_array($users_info['usertype_id'], array(24,31,25,30,12)))
                        $users['landlord'][] = array('id' => $id, 'name' => trim(ucwords($first_name . " " . $last_name)));
                    else if ($users_info['usertype_id'] == 23)
                        $users['sp'][] = array('id' => $id, 'name' =>trim(ucwords($first_name . " " . $last_name)));                    
                } else {
                    $users['all'][] = array('id' => $id, 'unit' => $unit_no, 'name' => trim(ucwords($first_name . " " . $last_name)), 'mobile_verification' => $users_info['mobile_verification'] , 'email_verified' => $users_info['email_verified']);   
                }

            }
            //asort($users);
        }

        return response()->json($users);

    }
    public function sendBroadcastMessage(Request $request){
        $request->validate([
            'message_type' => 'required',
            'description' => 'required',
            'users' => 'required'
        ]);
        
        $services = '';
        if($request->has('services') && count($request->services) > 0){
            foreach($request->services as $service){
                if($service == 1) $services = $service . ',';
            }
        }


        $broadcast_message = BroadcastMessage::create([
            'message_type' => $request->message_type,
            'description' => $request->description,
            'template_type' => $request->template_type,
            'creator_id' => Auth::id(),
            'modifier_id' => Auth::id(),
            'services' => $services,
            'building_id' => $request->building_id,
            'exp_date' => $request->has('expiry') ? date("Y-m-d h:i:s", strtotime($request->expiry)) : null
        ]);

        $broadcast_message->update(['first_message_id' => $broadcast_message->id]);

        foreach($request->users as $user){
            $user_data = User::find($user);

            if( ($request->message_type == 'email' && $user_data) || ($request->message_type == 'both' && $user_data)  ){
                $msg = $this->dynamicData($request->description, $user_data);
                sendEmail($msg,$user_data->email,null,"Broadcast Message",'',false);
            }
            if( ($request->message_type == 'sms' && $user_data) || ($request->message_type == 'both' && $user_data) ){
                $msg = $this->dynamicData($request->description, $user_data);
                sendSMS($msg,$user_data->mobile);
            }

            BroadcastMessageUser::create([
                'message_id' => $broadcast_message->id,
                'user_id' => $user,
                'label' => 'unread',
                'sent' => 0,
                'is_archived' => 0,
                'creator_id' => Auth::id(),
                'modifier_id' => Auth::id()
            ]);

            if($request->template_type == 'v') {
                $this->start_sequence($user, $broadcast_message->id, $request->has('expiry') ? date("Y-m-d", strtotime($request->expiry)) : null);
            }
        }
        return response()->json(['message' => 'Broadcast message sent successfully.']);
    }
    public function createBroadcastTemplate(Request $request){
        $request->validate([
            'message_type' => 'required',
            'template_type' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);
        
        BroadcastTemplate::create([
            'message_type' => $request->message_type,
            'template_type' => $request->template_type,
            'title' => $request->title,
            'description' => $request->description,
            'system_default' => 'n',
            'created_by' => Auth::id(),
            'active' => 1,
            'created' => Carbon::now()
        ]);

        return response()->json(['status' => true,  'message' => 'Template saved successfully.']);
    }

    public function getBroadcastTemplates(Request $request){
        $templates = BroadcastTemplate::where('created_by',Auth::id())->where('template_type',$request->template_type)
                    ->when($request->message_type == 'both', function($q){
                        $q->where(function($sub){
                            $sub->where('message_type','sms')->orWhere('message_type','email');
                        });
                    })
                    ->when($request->message_type != 'both', function($q) use($request){
                        $q->where('message_type',$request->message_type);
                    })
                    ->get();
        return $templates;
    }

    private function dynamicData($text, $user){

        $text = str_replace('{MANAGEMENT}', 'Important', $text);
        $text = str_replace('{NAME}', $user['firstname'], $text);

        // $verify_url = $url = Router::url(
        //     ['controller' => 'users', 'action' => 'verification_page', md5($user['User']['email'])],
        //     true
        // );
        $verify_url = config('app.portal_url').'users/verification_page/'.md5($user['email']);
        
        $str = '';
        
        if ($user['mobile_verification'] == 'Yes'){
            $str .= "SMS: Verified\n\n";
        } else {
            $str .= "SMS: Verify mobile NOW. REPLY with two words \"VERIFY\" & your \"LASTNAME\" \n\n";
        }
        
        if ($user['email_verified']){
            $str .= "Email: Verified\n";
        } else {
            $str .= "Email: Not Verified ".$verify_url."\n";
        }
        
        $text = str_replace('{SMS_AND_EMAIL}', $str, $text);
        $text = str_replace('{STATUS_PAGE}', $verify_url, $text);
        
        return $text;
    }

    public function start_sequence($user_id, $message_id = null, $z_date = null){


        $user = VerificationSequence::where('user_id',$user_id)->where('status','unverified')->get();
        
        if (count($user) == 0){

            if (!is_null($z_date)){
                $z_date = date("Y-m-d", strtotime($z_date));
            } else {
                $building = Building::
                                join('units','units.building_id','=','buildings.id')
                                ->leftJoin('units_users','units_user.unit_id','=','units.id')
                                ->leftJoin('users','users.id','=','units_users.user_id')
                                ->where('users.id',$user_id)
                                ->first();

                $zee_date = count($building) && isset($building['z_date']) ? $building['z_date'] : 1000; 
                $z_date=date('Y-m-d', strtotime("+".$zee_date." days"));
            }

            if ($z_date){
                
                $data['user_id']= $user_id;
                $data['z_date'] = $z_date;
                $data['message_id'] = $message_id;
                $data['start_date'] = date('Y-m-d');
                VerificationSequence::create($data);
            }
           
        }
        
    }


}
