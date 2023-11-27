<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;


class ResidentListingParser
{
    /**
     * @var Building
     */
    public $query;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        // $this->applySearchKeywordFilter($request);
        // $this->applyBuildingFilter($request);
        // $this->applyDateFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return void
     */
    public function applyDateFilter($request)
    {
        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];

        if ($from && $to) {
            $this->query = $this->query->where('workorders.created', '>=', $from);
            $this->query = $this->query->where('workorders.created', '<=', $to);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        $building_id = $request->building_id;
        $this->query = $this->query->where('building_id', $building_id);
    }

    /**
     * @param $request
     * @return void
     */
    public function applySort($request)
    {
        if (isset($request->sortBy) && $request->sortBy != "") {
            $sortOrder = $request->sortOrder ?? 'asc';
            $this->query = $this->query->orderBy($request->sortBy, $sortOrder);
        } else {
            $this->query = $this->query->orderBy('id', 'desc');
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applySearchKeywordFilter($request)
    {
        if (isset($request->searchKeyword) && $request->searchKeyword != "") {
            $var = $request->searchKeyword;
            // $this->query = $this->query->where();
        }
    }

    public function getBaseQuery($request = null)
    {
        $building_id = $request->building_id;
        $unit_ids = Unit::where('building_id',$building_id)->pluck('id')->toArray();

        $query = UnitUser::
                    whereHas('user', function($query) use($request) {
                            $query->select('id', 'firstname', 'lastname', 'mobile','email','profile_picture')->where('status_delete',0); 
                            $query->when($request->search && $request->search != '' , function($q) use($request){
                                $q->where('firstname','LIKE',"%".$request->search."%");
                                $q->orWhere('lastname','LIKE',"%".$request->search."%");
                                $q->orWhere('email','LIKE',"%".$request->search."%");
                                $q->orWhere('mobile','LIKE',"%".$request->search."%");
                            });
                    })->
                    with([
                        'user'=> function ($query) use($request) { 
                            $query->select('id', 'firstname', 'lastname', 'mobile','email','profile_picture')->where('status_delete',0); 
                            $query->when($request->search && $request->search != '' , function($q) use($request){
                                $q->where('firstname','LIKE',"%".$request->search."%");
                                $q->orWhere('lastname','LIKE',"%".$request->search."%");
                                $q->orWhere('email','LIKE',"%".$request->search."%");
                                $q->orWhere('mobile','LIKE',"%".$request->search."%");
                            });
                        },
                        'unit' => function ($query) {
                            $query->select('id','unit_no');
                        }
                    ])
                    ->whereIn('unit_id',$unit_ids);
        return $query;
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page;

        $total = $this->query->count();
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
}
