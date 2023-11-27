<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Tasks;
use App\Models\UnitUser;
use App\Models\User;
use App\Models\UserTypes;
use App\Services\Parsers\TaskParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request){
        $data = app(TaskParser::class)->parse($request);
        return response()->json($data);
    }

    public function getTasksCount(Request $request){

        $user_id = Auth::id();
        $user = User::find($user_id);
        $usertype_id = $user->usertype_id ?? null;
        $selectedBuilding = $request->buildingId;

        if ($usertype_id == UserTypes::$owner) {
            $buildings_array = Building::where('user_id', $user->id)->pluck('id')->toArray();
        } else {
            $buildings_array = $user->buildingIds();
        }
        $tasks = Tasks::with(['TaskTemplate','createdBy'])->where([
            'tasks.status'    => 1,
        ])->where('task_state','!=','C');

        if ($usertype_id != 1) {
            $tasks = $tasks->whereIn('building_id', $buildings_array);
        }

        return response()->json(['count' => $tasks->count()]);
    }

    public function changeTaskState(Request $request){
        $task = Tasks::find($request->id);
        if ($request->state == 'completed') {
            // Task is completed
            $task->update(['task_state' => "C" , 'modified_by' => Auth::id()]);
        } elseif ($request->state == 'created') {
            // Task created today
            $task->update(['task_state' => "O", 'modified_by' => Auth::id()]);
        } elseif ($request->state == 'working') {
            // Task created within the past week
            $task->update(['modified' => Carbon::now()->subDays(4), 'task_state' => "O", 'modified_by' => Auth::id()]);
        } else {
            // Task created more than a week ago
            $task->update(['modified' => Carbon::now()->subDays(10), 'task_state' => "O", 'modified_by' => Auth::id()]);
        }

    }

    public function loadUnitUsers($unit_id){
        $users = UnitUser::
                            // select('users.firstname','users.lastname','users.id')
                            select(\DB::raw("CONCAT(users.firstname, ' ', users.lastname) as name") , 'users.id')
                            ->leftJoin('users','users.id','=','units_users.user_id')
                            ->where('unit_id',$unit_id)->get();
        return response()->json(['users' => $users]);
    }
}
