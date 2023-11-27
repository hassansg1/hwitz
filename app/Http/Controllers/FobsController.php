<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Building;
use App\Models\Token;
use App\Models\TokenHistory;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use App\Services\Parsers\AssignedFobsParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FobsController extends Controller
{
    public function getAssignedFobs(Request $request)
    {
        $data = app(AssignedFobsParser::class)->parse($request);
        return response()->json($data);
    }

    public function getUnAssignedFobs(Request $request)
    {
        $building_id = $request->building_id ?? 0;
        $tokens = Token::with('history.user')->select('tokens.id', 'tokens.card_id', 'tokens.user_id', 'tokens.is_active', 'buildings.name as buildings_name', 'buildings.id as buildings_id', 'buildings.address1'
            , 'buildings.address2', 'buildings.city')
            ->join('buildings_tokens', 'tokens.id', '=', 'buildings_tokens.token_id')
            ->join('buildings', 'buildings_tokens.building_id', '=', 'buildings.id')
            ->leftJoin('users', 'users.id', 'tokens.user_id')
            ->leftJoin('units_users', 'units_users.user_id', 'users.id')
            ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
            ->where('tokens.user_id', 0)
            ->whereIn('buildings_tokens.building_id', [$building_id])
            ->where(function ($q) use ($building_id) {
                $q->whereIn('units.building_id', [$building_id])->orWhere('tokens.user_id', 0);
            });

        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $tokens->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $tokens->orderByRaw('(CASE WHEN tokens.user_id > 1 then 1 WHEN tokens.user_id = 0 then 2 WHEN tokens.user_id = -1 then 3 ELSE 4 END), tokens.card_id');
        }

        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = count($tokens->get());
        $data = $tokens->skip($perPage * $currentPage)->take($perPage)->get()->toArray();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);

        
        $returnObj['data'] = $data;
        $data = $returnObj;

        return response()->json($data);

    }

    public function getDiscontinuedFobs(Request $request){
        $building_id = $request->building_id ?? 0;
        $owner = Building::find($building_id);
        $tokens = Token::with(['historyUser','modifiedBy'])->select('tokens.*','users.id as users_user_id','usertypes.name as usertypes_name')
            ->leftJoin('users','users.id','=','tokens.user_id')->leftJoin('usertypes','usertypes.id','=','users.usertype_id')
            ->leftJoin('buildings_tokens','buildings_tokens.token_id','tokens.id')

            ->where(function($q){
                $q->where(function($q1){
                    $q1->where('tokens.user_id','>',0)->whereNotNull('tokens.user_id')->where('tokens.mfob_status',0)
                    ->whereNotIn('user_id', function ($q){
                        $q->select('user_id')->distinct('user_id')->from('units_users');
                    });
                })->orWhere('tokens.user_id',-1);
            })
            
            ->whereIn('buildings_tokens.building_id', function($q) use ($owner){
                $q->select('id')->from('buildings')->where('buildings.user_id',$owner['user_id'] ?? 0);
            });

        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $tokens->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $tokens->groupBy('tokens.id');
        }

        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = count($tokens->get());
        $data = $tokens->skip($perPage * $currentPage)->take($perPage)->get()->toArray();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);

        
        $returnObj['data'] = $data;
        $data = $returnObj;

        return response()->json($data);
    }

    public function remove_and_recycle(Request $request, $id)
    {
        $token = Token::find($id);
        $token->update([
            'user_id' => 0,
            'is_active' => 1,
            'user_history' => $token['user_id'] > 0 ? $token['user_id'] : null
        ]);
        $building_id = $request->building_id ?? 0;
        $fob_text = ($token['mfob_status'] == 0) ? 'FOB ' : 'mFOB ';
        $TokenHistory['token_id'] = $id;
        $TokenHistory['building_id'] = $building_id;
        $TokenHistory['user_id'] = $token['user_history'];
        $TokenHistory['action_date'] = date("Y-m-d H:i:s");

        $TokenHistory['action'] = $fob_text . $token['card_id'] . ' Recycled';
        $TokenHistory['action_type'] = 7;
        $TokenHistory['unit_id'] = null;
        $TokenHistory['action_by'] = Auth::id();

        TokenHistory::create($TokenHistory);

    }
    public function get_entry_history(Request $request, $token_id = null)
    {

        $assets = Asset::select('assets.name', 'assets_log.event_type', 'assets_log.timestamp')
            ->join('assets_log', 'assets_log.asset_id', '=', 'assets.id')
            ->where('assets_log.token_id', $token_id)
            ->whereIn('assets_log.event_type', ["Activate", "Deactivate", "Remove"]);

        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $assets->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $assets->orderBy('assets_log.timestamp', 'desc');
        }

        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = count($assets->get());
        $data = $assets->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $data;

        $data = $returnObj;

        return response()->json($data);

    }

    public function remove_permanently(Request $request, $id)
    {

        $token = Token::find($id);
        Token::where('id', $id)->update([
            'user_id' => -2,
            'is_active' => 0,
            'user_history' => $token['user_id'] > 0 ? $token['user_id'] : ''
        ]);
        $building_id = $request->building_id ?? 0;
        $fob_text = ($token['mfob_status'] == 0) ? 'FOB ' : 'mFOB ';
        $TokenHistory['token_id'] = $id;
        $TokenHistory['building_id'] = $building_id;
        $TokenHistory['user_id'] = $token['user_history'];
        $TokenHistory['action_date'] = date("Y-m-d H:i:s");

        $TokenHistory['action'] = $fob_text . $token['card_id'] . ' Permanently Discarded';
        $TokenHistory['action_type'] = 8;
        $TokenHistory['unit_id'] = null;
        $TokenHistory['action_by'] = Auth::id();
        TokenHistory::create($TokenHistory);

    }

    public function get_fob_history(Request $request, $token_id = 301)
    {
        $token_history = TokenHistory::select('tokens_histories.*', 'tokens.*', 'PerformedBy.firstname as PerformedBy_firstname', 'PerformedBy.lastname as PerformedBy_lastname', 'units.unit_no', 'buildings.name', 'Resident.firstname as Resident_firstname', 'Resident.lastname as Resident_lastname', 'FOBModels.description as FOBModels_description', 'mFOBModels.description as mFOBModels_description')
            ->join('tokens', 'tokens.id', 'tokens_histories.token_id')
            ->join('users as PerformedBy', 'PerformedBy.id', 'tokens_histories.action_by')
            ->leftJoin('units', 'units.id', 'tokens_histories.unit_id')
            ->leftJoin('buildings', 'buildings.id', 'tokens_histories.building_id')
            ->leftJoin('models as FOBModels', 'tokens.model_id', 'FOBModels.id')
            ->leftJoin('models as mFOBModels', 'buildings.mfob_model_id', 'mFOBModels.id')
            ->leftJoin('users as Resident', 'Resident.id', 'tokens_histories.user_id')
            ->where('tokens_histories.token_id', $token_id);

        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $token_history->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $token_history->orderBy('tokens.id', 'desc');
        }

        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = count($token_history->get());
        $data = $token_history->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $data;

        $data = $returnObj;


        $token_detail = Token::find($token_id);

        return response()->json($data);
    }

    public function loadUsers(Request $request){
        $building_id = $request->building_id;
        $unit_ids = Unit::where('building_id',$building_id)->pluck('id')->toArray();
        // $unit_ids = [375];
        // $query = UnitUser::
        //             with([
        //                 'user'=> function ($query) { 
        //                     $query->select('id', 'firstname', 'lastname', 'mobile','email','profile_picture')->where('status_delete',0); 
        //                 },
        //                 'unit' => function ($query) {
        //                     $query->select('id','unit_no');
        //                 }
        //             ])
        //             ->whereIn('unit_id',$unit_ids);

        $users = User::
                        select(DB::raw("CONCAT(users.firstname, ' ', users.lastname) as fullname") , 'users.id','units_users.unit_id')
                        ->leftJoin('units_users','users.id','=','units_users.user_id')
                        // ->whereNotIn('users.id', function ($query) {
                        //     $query->select('tokens.user_id')
                        //         ->from('tokens');
                        // })
                        ->whereIn('units_users.unit_id',$unit_ids)
                        ->get();
        return $users;
    }
}
