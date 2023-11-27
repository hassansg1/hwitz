<?php
namespace App\Libs;

use DB;

use App\Models\Cameras;
use App\Models\DownTimeLog;

class CameraAPI {


    public $verbose = false;

    public $host = null;
    public $user = null;
    public $pass = null;

    /**
     *      * Create a new command instance.
     *           *
     *                * @return void
     *                     */
    public function __construct() {
        $this->host = '172.17.14.9';
        $this->user = 'muscron';
        $this->pass = 'mus-crete';
    }

    /**
     *      * CURL call to access EPC
     *           *
     *                */
    public function URL($url, $timeout = 15) {

        $ch = curl_init();
        if ($this->user) {
            curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->pass");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,$timeout); 
        //curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/camera_cookie.txt');
        //curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/camera_cookie.txt');

        $buf2 = curl_exec ($ch);
        if ($buf2) {
            dde(" camera: $url: size: ".strlen($buf2),'camera.log');
        }
        else {
            dde(" camera: Error: ".curl_error($ch), 'camera.log');
        }

        curl_close ($ch);

        return $buf2;
    }

    public function getPicture($id = null) {
        if ($id) {
            $cam = Cameras::find($id);
            if ($cam->snapshot_jpg) {
                return $cam->snapshot_jpg;
            }
            elseif ($cam->snapshot_url) {
                $this->user = $cam->view_user;
                $this->pass = $cam->view_pass;
                if ($cam->device_id) {
                    $device_id = $cam->device_id;
                    $device = $cam->device;
                }
                else {
                    $device_id = 3;
                    $device = $id;
                }
                $rec = DownTimeLog::select('status')
                    ->where('device_id', '=', $device_id)
                    ->where('device', $device)
                    ->orderBy('timestamp', 'desc')
                    ->first();

                if (php_sapi_name() === 'cli')
                    $log = 'cli_camera.log';
                else
                    $log =  'camera.log';
                // $lq = DB::getQueryLog(); $lq = end($lq); dde($lq, $log);

                if ($rec && $rec->status == 1)
                    return($this->URL($cam->snapshot_url));
                else
                    return(null);
            }
            else {
                echo "Missing snapshot_url";
            }
        }
        else
            echo "Missing Info\n";

        return null;
    }

}

