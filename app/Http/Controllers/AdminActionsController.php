<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Building;
use App\Services\Parsers\OwnersParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminActionsController extends Controller
{
    public function __construct()
    {

        $this->middleware('check_admin');
    }

    /**
     * admin dasboard
     */
    public function dashboard()
    {

        $owners = User::where('usertype_id', 15)->get();
        $buildings = Building::where('status', 1)->get();

        return view('admin_actions.dashboard', compact('owners', 'buildings'));
    }

    public function owners(Request $request)
    {
        $data = app(OwnersParser::class)->parse($request);

        return response()->json($data);
    }

    protected function loginAs($userId)
    {
        $adminId = Auth::id();
        $user = User::find($userId);
        if ($user->email) {
            Auth::loginUsingId($user->id, true);

            if (!Session::get('admin_id')) {
                Session::put('admin_id', $adminId);
                session()->put('admin_id', $adminId);
            }

            return redirect('/portfolio');
        }
        return redirect()->back();
    }
}
