<?php

namespace App\Http\Controllers;

use App\Models\AccessPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AclController extends Controller
{
    //
    public function getPermissions()
    {
        $permissions = getUserPermissions();

        return response()->json([
            "status" => true,
            "data" => $permissions
        ]);
    }
}
