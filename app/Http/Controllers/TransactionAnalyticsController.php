<?php

namespace App\Http\Controllers;

use App\Services\Parsers\TransactionReportParser;
use Illuminate\Http\Request;

class TransactionAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(TransactionReportParser::class)->parse($request);

        return response()->json($data);
    }
}