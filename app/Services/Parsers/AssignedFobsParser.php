<?php

namespace App\Services\Parsers;

use App\Exceptions;
use App\Models\Building;
use App\Models\ScheduleName;
use App\Models\Token;
use App\TokenHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignedFobsParser{

     /**
     * @var Building
     */
    public $query;
    public $building;
    public $request;
    public $owner;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->request = $request;
        $this->getOwner();
        $this->query = $this->getBaseQuery($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        $data = $this->processData($data);
        return $data;

    }      
    public function getOwner(){
        $building_id = $this->building = $this->request->building_id ?? 0;
        $this->owner = Building::find($building_id);
    }
    /**
     * getBaseQuery
     *
     * @return void
     */
    public function getBaseQuery()
    {
        $owner = $this->owner;
        
        $q = Token::select('tokens.id as token_idd','tokens.card_id','tokens.user_id','tokens.created','tokens.start_date','tokens.end_date','tokens.is_active','buildings.id as buildings_id','buildings.name as buildings_name','buildings.address1','buildings.address2','buildings.city','users.firstname','users.lastname','users.mobile','users.email','users.profile_picture','mobile_verification','email_verified')
            ->join('buildings_tokens','tokens.id','=','buildings_tokens.token_id')
            ->join('buildings','buildings.id','=','buildings_tokens.building_id')
            ->leftJoin('users','users.id','tokens.user_id')
            ->leftJoin('units_users','users.id','units_users.user_id')
            ->leftJoin('units','units.id','units_users.unit_id')
            ->whereIn('buildings_tokens.building_id',[$this->building])
            ->where('tokens.user_id','>',0)
            ->where(function($q){
                $q->whereIn('units.building_id',[$this->building])->orWhere('tokens.user_id',0);
            })
            ->groupBy('tokens.id');
            return $q;
    }
    
    /**
     * applySort
     *
     * @param  mixed $request
     * @return void
     */
    public function applySort($request)
    {
        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $this->query->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $this->query = $this->query->orderByRaw('(CASE WHEN tokens.user_id > 1 then 1 WHEN tokens.user_id = 0 then 2 WHEN tokens.user_id = -1 then 3 ELSE 4 END), tokens.card_id');
        }
    }
    
    /**
     * paginate
     *
     * @param  mixed $request
     * @return void
     */
    public function paginate($request)
    {
        $perPage = $request->totalItems ??  getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = count($this->query->get());
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $data;
        return $returnObj;
    }
    
    /**
     * processData
     *
     * @param  mixed $tasks
     * @return void
     */
    public function processData($fobs){
        $data = $fobs['data']->toArray(); 
        for ($q=0; $q < count($data); $q++) {
            
            $result = Token::select(DB::raw('tokens.id','tokens.card_id','tokens.user_id','tokens.is_active',
                                    'buildings.id as buildings_id','buildings.name as buildings_name','buildings.address1','buildings.address2','buildings.city',
                                    'users.id','users.firstname','users.lastname',
                                    'units.id as units_id','units.unit_no',
                                    'usertypes.name as usertypes_name',
                                    'GROUP_CONCAT(assets.id,":",IFNULL(assets_tokens_status.event_type,"Unassigned"),":",assets.name) as asset_ids')
                            )
                            ->join('users','users.id','=',DB::raw('users.id = IF(tokens.user_id < 0,tokens.user_history,tokens.user_id)'))
                            ->join('usertypes','usertypes.id','=','users.usertype_id')
                            ->join('units_users','units_users.user_id','=','users.id')
                            ->join('units','units.id','=','units_users.unit_id')
                            ->join('buildings','buildings.id','=','units.building_id')
                            ->join('buildings_tokens', function($q){
                                $q->on('tokens.id','=','buildings_tokens.building_id');
                                $q->on('buildings.id','=','buildings_tokens.building_id');    
                            })
                            ->join('assets','assets.building_id','=','buildings.id')
                            ->join('assets_tokens_status',function($q){
                                $q->on('assets_tokens_status.token_id','=','buildings_tokens.token_id');
                                $q->on('assets_tokens_status.asset_id','=','assets.id');    
                            })
                            ->where('tokens.id',$data[$q]['token_idd'])
                            ->where('units.building_id',$data[$q]['buildings_id'])
                            ->groupBy('tokens.id')
                            ->first();
        

            $old_data = $data[$q];
            if ($result) {
                $data[$q] =  $result;
            }

            $data[$q]['History']['unit_id'] = isset($data[$q]['units_id']) ? $data[$q]['units_id'] : null;
            $data[$q]['History']['unit_no'] = isset($data[$q]['unit_no']) ? $data[$q]['unit_no'] : null;
            $data[$q]['History']['firstname'] = isset($data[$q]['firstname']) ? $data[$q]['firstname'] : null;
            $data[$q]['History']['lastname'] = isset($data[$q]['lastname']) ? $data[$q]['lastname'] : null;
            $data[$q]['History']['user_id'] = isset($data[$q]['users_id']) ? $data[$q]['users_id'] : null;
            //   $data[$q]['History']['action_date'] = isset($data[$q]['TokenHistory']['action_date']) ? $data[$q]['TokenHistory']['action_date'] : null;
            $data[$q]['History']['building_name'] = isset($data[$q]['buildings_name']) ? $data[$q]['buildings_name'] : $old_data['buildings_name'] ;
            $data[$q]['History']['building_address'] = isset($data[$q]['address1']) ? $data[$q]['address1'] : $old_data['address1'];
            $data[$q]['History']['building_id'] = isset($data[$q]['buildings_id']) ? $data[$q]['buildings_id'] : $old_data['buildings_id'];
            $data[$q]['History']['building_city'] = isset($data[$q]['city']) ? $data[$q]['city'] : $old_data['city'];
            $data[$q]['History']['author_type'] = isset($data[$q]['usertypes_name']) ? $data[$q]['usertypes_name'] : null;
        }

        $fobs['data'] = $data;
        return $fobs;
        
    }
    




}