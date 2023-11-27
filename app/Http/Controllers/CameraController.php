<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Cameras;
use App\Services\Parsers\DeviceParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CameraController extends Controller
{
    public function index($buildingId)
    {
        if ($buildingId) {
            $cams = Cameras::select(
                'id',
                'name',
                'snapshot_last',
                'video_last',
                'rtsp_url',
                'video_recordings')
                ->where('building_id', $buildingId)
                ->where('snapshot_url', '>', '')
                ->where('active', 1)
                ->orderBy('name')
                ->get();
            foreach ($cams as $index => $cam) {
                $cam->snapshot_age = time() - strtotime($cam->snapshot_last);
                $cam->video_age = time() - strtotime($cam->video_last);
                $cams[$index] = $cam;
            }

            return response()->json([
                "status" => true,
                "cameras" => $cams
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "No Building Selected"
        ]);
    }

    public function cameraDetails($cameraId)
    {
        $buildingId = Session::get('building_id');
        if ($cameraId) {
            $cam = Cameras::select(
                'id',
                'name',
                'snapshot_last',
                'video_last',
                'rtsp_url',
                'video_recordings')
                ->where('id', $cameraId)
                ->where('building_id', $buildingId)
                ->where('snapshot_url', '>', '')
                ->where('active', 1)
                ->orderBy('name')
                ->first();
            $cam->snapshot_age = time() - strtotime($cam->snapshot_last);
            $cam->video_age = time() - strtotime($cam->video_last);

            return response()->json([
                "status" => true,
                "camera" => $cam,
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "No Camera Selected"
        ]);
    }
}