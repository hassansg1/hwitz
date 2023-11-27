<?php

namespace App\Http\Controllers;

use App\Models\SSO;
use AWS\CRT\HTTP\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SsoController extends Controller
{
    //
    public function permissions()
    {
        if (!hasPermission("permissions")) {
            abort(401);
        }
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/users_permissions';
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

    //
    public function manageCarts()
    {
        $buildingId = Session::get("building_id");
        if (!hasPermission("carts")) {
            abort(401);
        }
        if (!$buildingId) {
            abort(403, "Building not Selected.");
        }
        $tab = "carts";
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/building/manage/' . $buildingId . '?tab=' . $tab;
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

    public function moreBuildingSettings()
    {
        $buildingId = Session::get("building_id");
        if (!hasPermission("building_settings")) {
            abort(401);
        }

        if (!$buildingId) {
            abort(403, "Building not Selected.");
        }
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/building/manage/' . $buildingId;
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

    public function unitHistory($unitId)
    {
        $url = rtrim(config('app.ATTENDANT_URL'), '/') . '/unit-history/' . $unitId;
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

    function get_ip()
    {
        $ip = NULL;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    private function addSso($url)
    {
        $user = Auth::user();

        SSO::where('user_id', $user->id)->where('ip', $this->get_ip())->delete();

        SSO::create([
            'user_id' => $user->id,
            'ip' => $this->get_ip(),
            'target_url' => $url,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function manageLockdown()
    {
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/lockdown/manage';
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

    public function ssoForAdmin($relative_url)
    {
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/' . $relative_url;
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

    public function unlockAssetDoor($id, $action)
    {
        // dump($id);dd($action);
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/asset/door_status/' . $id . '/' . $action;
        $this->addSso($url);
        header("Location: " . $url);
        echo 'done';
        exit;
    }

    public function manageBuilding($id)
    {
        $url = rtrim(config('app.ADMIN_URL'), '/') . '/building/manage/' . $id . '/r';
        $this->addSso($url);
        header("Location: " . $url);
        exit;
    }

}
