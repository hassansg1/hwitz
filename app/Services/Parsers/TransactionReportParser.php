<?php

namespace App\Services\Parsers;


use App\Models\Building;
use App\Models\TransactionLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionReportParser
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
        $this->applyBuildingFilter($request);
        $this->applyUnitFilter($request);
        $this->applyUniversalBuildingFilter($request);
        $this->applyDatesFilter($request);
        $this->applyPayAbleFilter($request);
        $this->applyReceiveAbleAbleFilter($request);
        $this->applyTypeFilter($request);
        $this->applyUserFilter($request);
        $this->applyCartFilter($request);
        $this->applyInvoiceTypeFilter($request);
        $this->applyBillTypeFilter($request);
        $data = $this->paginate($request);
        return $data;

    }

    /**
     * @param $request
     * @return TransactionLog|Builder
     */
    public function getBaseQuery($request = null)
    {
        return TransactionLog::with(
            array(
                'linkableitems',
                'paymentLink',
                'receiver',
                'addon',
                'feature',
                "building",
                "unit",
                "transSource" => function ($query) {
                    $query->select('id', 'description');
                }))
            ->
            select(
                'id',
                'timestamp',
                'building_id',
                DB::raw("MONTHNAME(timestamp) month"),
                'amount',
                'balance',
                'notes',
                'trans_source_id',
                'receiver_id',
                'is_cleared',
                'payment_link_id',
                'comment',
                'invoice_type',
                'addon_id',
                'feature_id',
                'unit_id'
            )
            ->orderBy('id', 'desc');
    }

    /**
     * @param $request
     */
    public function applyBillTypeFilter($request)
    {
        if (Auth::user()->usertype_id == 15) {
            $this->query->where('comment', 'Resident Bill');
        }
        if (Auth::user()->usertype_id == 23) {
            $this->query->where('comment', 'Resident Amenities Bill');
        }
    }

    /**
     * @param $request
     */
    public function applyInvoiceTypeFilter($request)
    {
        if (isset($request->invoiceType) && $request->invoiceType) {
            $invoiceTypeArray = explode(',', $request->invoiceType);
            if (count($invoiceTypeArray) == 1 && $invoiceTypeArray[0] == "all_transactions") {
                $this->query->where(function ($q) {
                    $q->whereNull('invoice_type');
                    $q->orWhere('invoice_type', 'receiver allocation');
                });
            } else {
                if (count($invoiceTypeArray) == 1 && $invoiceTypeArray[0] == "all_taxes") {
                    $this->query = $this->query->where('is_tax', 1);
                } else {
                    if ($request->deleted) {
                        $this->query->whereHas('paymentLink', function ($query) use ($request) {
                            if ($request->deleted == "true") {
                                $query->where('status', '!=', 'deleted');
                            }
                        });
                    }
                    $this->query = $this->query->whereIn('invoice_type', $invoiceTypeArray);
                }
            }
        }
    }

    /**
     * @param $request
     */
    public function applyCartFilter($request)
    {
        if (isset($request->cart) && $request->cart) {
            $this->query = $this->query->where('trans_source_id', $request->cart);
        }
    }

    /**
     * @param $request
     */
    public function applyUserFilter($request)
    {
        if (isset($request->user) && $request->user) {
            $this->query = $this->query->where('user_id', $request->user);
        }
    }

    /**
     * @param $request
     */
    public function applyTypeFilter($request)
    {
        if (isset($request->type) && $request->type) {
            if ($request->type == "payment")
                $this->query = $this->query->where('amount', '>', 0);
            else if ($request->type == "charge")
                $this->query = $this->query->where('amount', '<', 0);
        }
    }

    /**
     * @param $request
     */
    public function applyReceiveAbleAbleFilter($request)
    {
        if (isset($request->receiveAble) && $request->receiveAble) {
            $this->query = $this->query->whereNotNull('receiver_id');
        }
    }

    /**
     * @param $request
     */
    public function applyPayAbleFilter($request)
    {
        if (isset($request->payAble) && $request->payAble) {
            $this->query = $this->query->whereNotNull('payment_link_id');
            $this->query = $this->query->whereNull('parent_transaction_log_id');
        }
    }

    /**
     * @param $request
     */
    public function applyUniversalBuildingFilter($request)
    {
        if ($request->universalBuildingId) {
            $this->query = $this->query->where('building_id', $request->universalBuildingId);
        }
    }

    /**
     * @param $request
     */
    public function applyBuildingFilter($request)
    {
        if ($request->buildingId) {
            if ($request->buildingId == "All") {
                $buildings = getBuildingsByOwner($request->ownerId)->pluck('id')->toArray();
                $this->query = $this->query->whereIn('building_id', $buildings);
            } else
                $this->query = $this->query->where('building_id', $request->buildingId);
        }
    }

    /**
     * @param $request
     */
    public function applyUnitFilter($request)
    {
        if ($request->unitId) {
            $this->query = $this->query->where('unit_id', $request->unitId);
        }
    }

    public function applyDatesFilter($request)
    {
        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];

        if ($from && $to) {
//            $this->query = $this->query->where('timestamp', '>=', $from);
//            $this->query = $this->query->where('timestamp', '<=', $to);
        }
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
