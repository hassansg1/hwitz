<?php

namespace App\Http\Controllers;

use App\Models\WalletHistoryLog;
use App\Models\Wallets;
use App\Services\Parsers\WalletParser;
use Illuminate\Http\Request;

class WalletAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(WalletParser::class)->parse($request);

        return response()->json($data);
    }

    public function walletActivityModal(Request $request)
    {
        $wallet = Wallets::find($request->id);
        $items = WalletHistoryLog::with('createdBy')->where('wallet_id', $request->id)->orderBy('created', 'desc')->get();


        return response()->json([
            'wallet' => $wallet,
            'items' => $items,
        ]);
    }
}