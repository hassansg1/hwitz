<?php

namespace App\Http\Controllers;

use App\Services\Parsers\TasksAnalyticsParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $data = app(TasksAnalyticsParser::class)->parse($request);

        return response()->json($data);
    }
}