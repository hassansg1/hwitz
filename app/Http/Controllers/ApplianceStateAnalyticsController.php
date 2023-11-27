<?php

namespace App\Http\Controllers;

use App\Services\Parsers\ApplianceStateParser;
use Illuminate\Http\Request;

class ApplianceStateAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(ApplianceStateParser::class)->parse($request);

        return response()->json($data);
    }
}