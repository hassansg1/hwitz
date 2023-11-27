<?php

namespace App\Http\Controllers;

use App\Models\StaffSubRoles;
use App\Models\SubRoles;
use App\Services\Parsers\StaffSubRolesParser;
use App\Services\Parsers\SystemUsersParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemUsersController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = app(SystemUsersParser::class)->parse($request);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function staffSubRoles(Request $request)
    {
        $data = app(StaffSubRolesParser::class)->parse($request);

        return response()->json($data);
    }

    public function addNewSubRoleModal(Request $request)
    {
        if (isset($request->id) && $request->id && $request->id != "")
            $request->validate([
                'name' => ['required'],
                'alias' => ['required'],
            ]);
        else
            $request->validate([
                'name' => ['required', 'unique:sub_roles'],
                'alias' => ['required', 'unique:sub_roles'],
            ]);

        if (isset($request->id)) {
            $subRole = SubRoles::find($request->id);
            $subRole->name = $request->name;
            $subRole->alias = $request->alias;
            $subRole->updated_by = Auth::id();
            $subRole->save();
            return response()->json(['status' => 'success', 'message' => 'Sub Role updated.']);
        } else {
            SubRoles::create([
                "name" => $request->name,
                "alias" => $request->alias,
                "created_by" => Auth::id(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sub Role saved.']);
        }
    }

    public function assignNewSubRole(Request $request)
    {
        $request->validate([
            'alias' => ['required'],
            'userId' => ['required'],
            'buildingId' => ['required'],
        ]);

        StaffSubRoles::updateOrCreate(
            [
                'user_id' => $request->userId,
                'building_id' => $request->buildingId,
                'sub_role' => $request->alias,
            ],
            [
                'user_id' => $request->userId,
                'building_id' => $request->buildingId,
                'sub_role' => $request->alias,
                'created_by' => Auth::id(),
            ]
        );
        return response()->json(['status' => 'success', 'message' => 'Sub Role updated.']);
    }

    public function deleteSubRoleAssignment(Request $request)
    {
        StaffSubRoles::where('id', $request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Sub role assignment delete successfully.']);
    }
}
