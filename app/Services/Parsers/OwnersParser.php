<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OwnersParser
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
        $this->applySearchKeywordFilter($request);
        $this->applyBuildingFilter($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }

    public function applyBuildingFilter()
    {
        $authBuildings = Auth::user()->buildings;
        if ($authBuildings && count($authBuildings) > 0) {
            $users = $authBuildings->pluck('user_id')->toArray();
            $this->query = $this->query->whereIn('id', $users);
        } else {
            $this->query = $this->query->whereIn('id', []);
        }
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
            $this->query->orderBy("users.id", "asc");
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
            $this->query = $this->query->where(function ($query) use ($var) {
                $query->orWhere('firstname', 'LIKE', '%' . $var . '%');
                $query->orWhere('middlename', 'LIKE', '%' . $var . '%');
                $query->orWhere('lastname', 'LIKE', '%' . $var . '%');
                $query->orWhere('mobile', 'LIKE', '%' . $var . '%');
                $query->orWhere('email', 'LIKE', '%' . $var . '%');
            });
        }
    }


    public function getBaseQuery($request = null)
    {
        return User::where('usertype_id', 15);
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? getEntriesPerPage();
        $currentPage = $request->current_page ?? 0;

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
