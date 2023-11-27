<?php

namespace App\Http\Controllers;

use App\Models\LockerPackage;
use App\Services\Parsers\DataParser;
use App\Services\Parsers\FobsImagesParser;
use App\Services\Parsers\FobsParser;
use App\Services\Parsers\FobsVersoParser;
use App\Services\Parsers\IntercomParser;
use App\Services\Parsers\LaundryParser;
use App\Services\Parsers\LockerPackagesParser;
use App\Services\Parsers\NumberDevicesParser;
use App\Services\Parsers\PhoneParser;
use App\Services\Parsers\SmsParser;
use App\Services\Parsers\TiltParser;
use Illuminate\Http\Request;

class NonAdminAnalyticsController extends Controller
{
    public function packageAnalytics(Request $request)
    {
        $data = app(LockerPackagesParser::class)->parse($request);

        return response()->json($data);
    }

    public function lockerPackageDetail(Request $request)
    {
        return json_encode(LockerPackage::find($request->id), JSON_INVALID_UTF8_IGNORE);
    }

    public function getBlobPhoto($id = null, $fieldName = null)
    {
        $photo = LockerPackage::select("$fieldName", $fieldName . "_type")->find($id);

        $picture = false;
        if (isset($photo) && $photo->{$fieldName . "_type"}) {
            header("content-type: " . $photo->{$fieldName . "_type"});
            $picture = $photo->{$fieldName};
        } elseif (isset($photo)) {
            $fp = @fopen($picture, "r");
            if ($fp !== false) {
                fclose($fp);
                $picture = $photo->{$fieldName};
            }
        }
        echo $picture;
        exit;
    }


    public function dataAnalytics(Request $request)
    {
        $data = app(DataParser::class)->parse($request);

        return response()->json($data);
    }

    public function fobsHidAnalytics(Request $request)
    {
        $data = app(FobsParser::class)->parse($request);

        return response()->json($data);
    }

    public function fobsHidImageAnalytics(Request $request)
    {
        $data = app(FobsImagesParser::class)->parse($request);

        return response()->json($data);
    }

    public function fobsVersoAnalytics(Request $request)
    {
        $data = app(FobsVersoParser::class)->parse($request);

        return response()->json($data);
    }

    public function loadEventsDataForVersoReport()
    {
        $doors_events_names = \Illuminate\Support\Facades\DB::table('doors_events_names')->get();
        $doors_event_types = \Illuminate\Support\Facades\DB::table('doors_event_types')->get();

        return response()->json(compact('doors_events_names', 'doors_event_types'));
    }

    public function intercomAnalytics(Request $request)
    {
        $data = app(IntercomParser::class)->parse($request);

        return response()->json($data);
    }

    public function laundryAnalytics(Request $request)
    {
        $data = app(LaundryParser::class)->parse($request);

        return response()->json($data);
    }

    public function phoneAnalytics(Request $request)
    {
        $data = app(PhoneParser::class)->parse($request);

        return response()->json($data);
    }

    public function smsAnalytics(Request $request)
    {
        $data = app(SmsParser::class)->parse($request);

        return response()->json($data);
    }

    public function tiltAnalytics(Request $request)
    {
        $data = app(TiltParser::class)->parse($request);

        return response()->json($data);
    }

    public function uniqueDevicesAnalytics(Request $request)
    {
        $data = app(NumberDevicesParser::class)->parse($request);

        return response()->json($data);
    }
}