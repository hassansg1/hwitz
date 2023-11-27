<?php

namespace App\Services\Parsers;


use App\Models\AclLogs;
use App\Models\CronLogs;
use App\Models\ExtensionLog;
use Illuminate\Database\Eloquent\Builder;

class ExtensionLogsParser
{
    /**
     */
    public $query;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        $this->applyLockerFilter($request);
        $this->applyDateFilter($request);
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
            $this->query = $this->query->where('created_at', '>=', $from);
            $this->query = $this->query->where('created_at', '<=', $to);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyLockerFilter($request)
    {
        if (isset($request->lockerId) && $request->lockerId && $request->lockerId != "")
            $this->query = $this->query->where('locker_id', $request->lockerId);
    }

    /**
     * @param $request
     * @return void
     */
    public function applySort($request)
    {
        if (isset($request->sortBy) && $request->sortBy != "") {
            $sortOrder = $request->sortOrder ?? 'desc';
            $this->query = $this->query->orderBy($request->sortBy, $sortOrder);
        } else {
            $this->query = $this->query->orderBy('id', 'desc');

        }
    }

    public function getBaseQuery($request = null)
    {
        $query = ExtensionLog::with(['locker', 'package' => function ($query) {
            $query->select('id', 'locker_id', 'building_id', 'provider_id', 'receiver_id', 'arrived_at', 'package_picture_type', 'is_received'
                , 'received_at', 'received_by', 'token_id', 'is_locked'
            );
        }, 'user']);
        return $query;
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
