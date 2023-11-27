<?php

namespace App\Services\Parsers;

use App\Models\AchBatch;
use App\Models\Building;
use App\Models\TransactionLog;
use App\Models\UncollectedIncome;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FinancialSummaryParser
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
        $building = null;
        if ($request->buildingId) {
            $building = Building::find($request->buildingId);
            $buildings = [$request->buildingId];
        } else {
            $buildings = getBuildingsByOwner(Auth::id());
            $buildings = $buildings->pluck('id');
        }

        $query = TransactionLog::whereIn('building_id', $buildings);
        $lastDate = getLastACHBatchDate();
        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];

        if ($lastDate)
            $query = $query->where('timestamp', '>', $from);

        if (isset($request->cartRadio) && $request->cartRadio && $request->cartRadio != "") {
            if ($request->cartRadio == 4)
                $cartArray = [$request->cartRadio, 1];
            else
                $cartArray = [$request->cartRadio];
        }

        if (isset($request->cartRadio) && $request->cartRadio && $request->cartRadio != "") {
            $query = $query->whereIn('trans_source_id', $cartArray);
        }

        $expense = clone $query;
        $expense = $expense->where('user_id', Auth::id())->where('amount', '<', 0)->whereNotNull('payment_link_id');
        $expenseTotal = clone $expense;
        $expenseTotal = abs($expenseTotal->sum('amount'));
        $expenseLaundry = clone $expense;
        $expenseLaundry = abs($expenseLaundry->where('trans_source_id', 1)->sum('amount'));
        $expenseAmenities = clone $expense;
        $expenseAmenities = abs($expenseAmenities->where('trans_source_id', 2)->sum('amount'));
        $expenseRent = clone $expense;
        $expenseRent = abs($expenseRent->where('trans_source_id', 3)->sum('amount'));


        $collected = clone $query;
        $collected = $collected->where('receiver_id', Auth::id())->whereNotNull('transactionid')->where('is_cleared', 0);
        $collectedTotal = clone $collected;
        $collectedTotal = $collectedTotal->sum('amount');
        $collectedLaundry = clone $collected;
        $collectedLaundry = $collectedLaundry->where('trans_source_id', 1)->sum('amount');
        $collectedAmenities = clone $collected;
        $collectedAmenities = $collectedAmenities->where('trans_source_id', 2)->sum('amount');
        $collectedRent = clone $collected;
        $collectedRent = $collectedRent->where('trans_source_id', 3)->sum('amount');

        $uncollected = UncollectedIncome::whereIn('building_id', $buildings);
        if (isset($request->cartRadio) && $request->cartRadio && $request->cartRadio != "") {
            $uncollected = $uncollected->whereIn('trans_source_id', $cartArray);
        }
        $uncollectedTotal = clone $uncollected;
        $uncollectedTotal = $uncollectedTotal->sum('amount');
        $uncollectedLaundry = clone $uncollected;
        $uncollectedLaundry = $uncollectedLaundry->where('trans_source_id', 1)->sum('amount');
        $uncollectedAmenities = clone $uncollected;
        $uncollectedAmenities = $uncollectedAmenities->where('trans_source_id', 2)->sum('amount');
        $uncollectedRent = clone $uncollected;
        $uncollectedRent = $uncollectedRent->where('trans_source_id', 3)->sum('amount');

        $incomeLaundry = $collectedLaundry + $uncollectedLaundry;
        $incomeAmenities = $collectedAmenities + $uncollectedAmenities;
        $incomeRent = $collectedRent + $uncollectedRent;

        return compact('collectedTotal', 'collectedLaundry', 'collectedAmenities', 'collectedRent'
            , 'expenseTotal', 'expenseLaundry', 'expenseAmenities', 'expenseRent', 'uncollectedAmenities', 'uncollectedRent',
            'uncollectedLaundry', 'uncollectedTotal', 'incomeLaundry', 'incomeAmenities', 'incomeRent', 'building'
        );
    }
}