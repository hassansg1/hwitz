<?php

namespace App\Http\Controllers;

use App\Services\Parsers\DocumentsParser;
use App\Services\Parsers\SearchParser;
use App\Services\Parsers\ChatsParser;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $totalCount = 0;

        if ($request->category == "all" || $request->category == "users") {
            $users = app(SearchParser::class)->parse($request);
            $totalCount += $users['total'] ?? 0;
        }

        if ($request->category == "all" || $request->category == "documents") {
            $documents = app(DocumentsParser::class)->parse($request);
            $totalCount += $documents['total'] ?? 0;
        }

        if ($request->category == "all" || $request->category == "chats") {
            $chats = app(ChatsParser::class)->parse($request);
            $totalCount += $chats['total'] ?? 0;
        }

        return response()->json([
            "status" => true,
            "users" => $users ?? null,
            "documents" => $documents ?? null,
            "chats" => $chats ?? null,
            "totalCount" => $totalCount ?? 0,
        ]);
    }
}
