<?php

namespace App\Http\Controllers;

use App\Libs\CameraAPI;
use Illuminate\Http\Request;

// Appliance Controller
class ApplianceController extends Controller
{

    public function getCameraView(Request $request, $id)
    {
        $capi = new CameraAPI();


        header('Content-Type: image/jpg');
        echo $capi->getPicture($id);

    }
}
