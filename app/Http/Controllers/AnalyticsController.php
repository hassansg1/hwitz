<?php

namespace App\Http\Controllers;

use App\Models\MailServiceProvider;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function loadMailServiceProviders(Request $request)
    {
        $data = MailServiceProvider::all();

        return response()->json($data);
    }

    public function loadAmazonUrl(Request $request)
    {
        return config('settings.S3_BASE_URL');
    }

    public function loadFiveImages(Request $request)
    {
        $images = getFiveImages($request->image);

        return response()->json([
            'status' => true,
            'images' => $images
        ]);
    }
}