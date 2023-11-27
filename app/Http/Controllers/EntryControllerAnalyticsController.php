<?php

namespace App\Http\Controllers;

use App\Services\Parsers\EntryControllerParser;
use Illuminate\Http\Request;

class EntryControllerAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(EntryControllerParser::class)->parse($request);

        return response()->json($data);
    }
}