<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\BuildingsUsers;
use App\Models\Feature;
use App\Models\IssueType;
use App\Models\StaffSubRoles;
use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\WorkOrder;
use App\Models\WorkOrderComment;
use App\Models\WorkOrderLog;
use App\Repos\LogsRepo;
use App\Services\Parsers\ArchivedWorkOrderParser;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class WorkOrderController extends Controller
{
    public function index(Request $request){
        $building_id = $request->building_id;
        
        $query = WorkOrder::with(['maintainer','createdBy','watcher','comments.createdBy','log'])->select('workorders.*', 'buildings.name as building_name', 'buildings.id as building_id','units.unit_no')
            ->leftJoin('users AS User', function ($join) {
                $join->on('workorders.created_by', '=', 'User.id')
                    ->where('User.status', '=', 1);
            })
            ->leftJoin('units_users', 'workorders.resident_id', '=', 'units_users.user_id')
            ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'units.building_id')
            ->whereNotNull('workorders.submitted')
            ->where(function($q) use ($building_id){
                $q->where('buildings.id', $building_id)->orWhere('resident_id',0);
            })

            ->when($request->has('status') && $request->status != '',function($q) use ($request){
                $q->where('workorders.status_id',$request->status);
            })
            ->when(!$request->has('status') || $request->status == '',function($q) use ($request){  
                $q->where('workorders.status_id','!=','Close');
            })
            ->when($request->has('priority') && $request->priority != '',function($q) use ($request){
                $q->where('workorders.priority',$request->priority);
            })
            // ->orderByRaw("DATEDIFF(DATE(NOW()), workorders.created) > workorders.expire_days DESC, DATEDIFF(DATE(NOW()), workorders.created) DESC");
            ->orderBy('id','desc');

        if(!$request->has('all')){
            $query = $query->limit(10);
        }

        // $results = $query->get();


        $perPage = $request->totalItems ??  getEntriesPerPage();
        $currentPage = (int)$request->current_page;

        $total = $query->count();
        $data = $query->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);


        $work_order_array = [
            'created' => [],
            'in_progress' => [],
            'expired' => [],
            'completed' => [],
        ];

        foreach($data as $work_order){
            $createdAt = Carbon::parse($work_order['created']);
            $now = Carbon::now();
            if ($work_order['status_id'] === 'Resolved') {
                $work_order_array['completed'][] = $work_order;
            }  elseif ($work_order['status_id'] === 'Due') {
                // work_order created within the past week
                $work_order_array['expired'][] = $work_order;
            } elseif ($work_order['status_id'] === 'Reopen' || $createdAt->isSameDay($now)) {
                $work_order_array['created'][] = $work_order;
            }else {
                $work_order_array['in_progress'][] = $work_order;
            }
        }

        $returnObj['data'] = $work_order_array;
        return $returnObj;
        
    }

    public function changeWorkOrderStatus(Request $request){
        $updated = WorkOrder::where('id',$request->id)->update(['status_id' => $request->state]);
        $columns = [];
        
        if($request->state == 'Resolved') $columns['resolved_date'] = Carbon::now()->format('Y-m-d');
        else if($request->state == 'Inprogress') $columns['inprocess_date'] = Carbon::now()->format('Y-m-d');
        else if($request->state == 'close') $columns['closed_date'] = Carbon::now()->format('Y-m-d');
        else if($request->state == 'Due') $columns['due_date'] = Carbon::now()->format('Y-m-d');
        
        LogsRepo::updateWorkOrderLog($request->id,$columns);

        return $updated;

    }

    public function addAttachments(Request $request, $id){
        $work_order = WorkOrder::find($id);
        if($work_order){
            $images = unserialize($work_order->getRawOriginal('images'));
            $files = $request->file('files'); 
            foreach($files as $file){
                $images[Carbon::now()->format('Y-m-d H:i:s')] = $this->uploadFileToS3($file);
            }
            $work_order->update(['images' => serialize($images)]);
            return 1;
        }
        return 0;
    }

    public function uploadFileToS3($file){
        $uniqueName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $filePath = Storage::disk('s3_workorder')->putFileAs('', new File($file), $uniqueName, 'public');

        // Output the uploaded file path
        return $filePath;
    }

    public function addComment(Request $request, $id){
        $request->validate([
            'comment' => 'required'
        ]);

        $work_order = WorkOrderComment::create([
            'description' => $request->comment,
            'created_by' => Auth::id(),
            'private' => 'No',
            'workorder_id' => $id
        ]);
    }

    public function getMaintenanceAndWatchersList($building_id){
        
        $maintenance = BuildingsUsers::select('users.id',DB::raw("CONCAT(firstname, ' ', lastname) AS name"))
                            ->leftJoin('users','users.id','buildings_users.user_id')
                            ->where('users.usertype_id',30)
                            ->where('buildings_users.building_id',$building_id)
                            ->get();
        return $maintenance;
    }

    public function addWorkOrder(Request $request){
        $requestData = $request->validate([
            'assign_maint' => 'sometimes',
            'watcher' => 'sometimes',
            'subject' => 'required',
            'description' => 'required',
            // 'issue_type_id' => 'required',
            'status_id' => 'required',
            'priority' => 'required',
            'expire_days' => 'required',
            'resident_id' => 'required',
            'how_long_issue' => 'sometimes'
        ]);
        

        // if (strpos($request->issue_type_id, "feature_") !== false) {
        //     foreach (WorkOrder::$global_service_providers as $key => $service_provider) {
        //         if (in_array($request->issue_type_id, $service_provider)) {
        //             $requestData['assign_maint'] = $key;
        //             break;
        //         }
        //     }
        // }
        $images = [];
        if($request->has('images') && count($request->images) > 0){
            $files = $request->file('images'); 
            foreach($files as $file){
                $images[Carbon::now()->format(config('app.PHP_DATE_TIME_FORMAT'))] = $this->uploadFileToS3($file);
            }
        }

        if($request->has('time_available')){
            $requestData['time_available'] = serialize($request->time_available);
        }
        
        $requestData['images'] = serialize($images);
        $requestData['created_by'] = Auth::id();
        $requestData['submitted'] = Carbon::now();
        
        $work_order = WorkOrder::create($requestData);

        WorkOrderLog::create([
            'created_by' => Auth::id(),
            'open_date' => Carbon::now()->format('Y-m-d'),
            // 'due_date' => Carbon::now()->addDays($work_order->expire_days)->format('Y-m-d'),
            'workorder_id' => $work_order->id,
            'status' => 'UNLOCK'
        ]);
    }

    public function getIssueTypes(){
        $issueTypeData = Feature::pluck('name', 'id')->mapWithKeys(function ($issueType, $issueTypeKey) {
            return ['feature_' . $issueTypeKey => $issueType];
        })->toArray();
        
        $issueTypeData2 = IssueType::pluck('issue_type', 'id')->mapWithKeys(function ($issueType, $issueTypeKey) {
            return ['other_' . $issueTypeKey => $issueType];
        })->toArray();
        
        $issueTypeData = array_merge($issueTypeData, $issueTypeData2);
        $list = [];
        $i=0;
        foreach($issueTypeData as $key => $data){
            $list[$i]['id'] = $key;
            $list[$i]['name'] = $data;
            $i++;
        }
        return $list;
    }

    public function getResidentsByUnit($id){
        $residents = UnitUser::select('users.id',DB::raw("CONCAT(firstname, ' ', lastname) AS name"))
                        ->leftJoin('users','users.id','=','units_users.user_id')
                        ->where('units_users.unit_id',$id)
                        ->get();
        return $residents;
    }
    public function archivedWorkOrders(Request $request){

        $data = app(ArchivedWorkOrderParser::class)->parse($request);

        return response()->json($data);
        $building_id = $request->building_id;
        $query = WorkOrder::with(['maintainer','createdBy','watcher','comments.createdBy'])->select('workorders.*', 'buildings.name as building_name', 'buildings.id as building_id','units.unit_no')
            ->leftJoin('users AS User', function ($join) {
                $join->on('workorders.created_by', '=', 'User.id')
                    ->where('User.status', '=', 1);
            })
            ->leftJoin('units_users', 'workorders.resident_id', '=', 'units_users.user_id')
            ->leftJoin('units', 'units.id', '=', 'units_users.unit_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'units.building_id')
            ->whereNotNull('workorders.submitted')
            ->where('buildings.id', $building_id)
            ->where('workorders.status_id','=','Close')
            ->orderBy('id','desc')->get();


        return $query;
    }

    public function sendEmail(Request $request, $id){
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required',
            'description' => 'required'
        ]);
        $data = WorkOrder::with(['createdBy','watcher','maintainer','resident'])->find($id)->toArray();

        $unit_users = UnitUser::where('user_id',$data['resident_id'])->first();
        $unit = Unit::select('id','unit_no','building_id')->where('id',$unit_users->unit_id )->first();
        $building = Building::find($unit['building_id'] ?? 0);
        $log = WorkOrderLog::where('workorder_id',$data['id'])->where('status','UNLOCK')->orderBy('id','desc')->first();
        $pdf = PDF::loadView('workorder', ['data' => $data, 'unit' => $unit , 'building' => $building, 'log' => $log]);

        $strtotime = strtotime(now());
        $filePath = 'app/workorder-'.$strtotime.'.pdf';   
        $pdf->save(storage_path($filePath));

        $subject = 'Workorder # '.$data['id'] .' - '.$request->subject;

        sendEmail($request->description,$request->email,null,$subject,storage_path($filePath),false);
        unlink(storage_path($filePath));
        return response()->json(['message' => 'Email sent.']);
    }


    public function downloadWorkOrderPDF(Request $request, $id){
        $data = WorkOrder::with(['createdBy','watcher','maintainer','resident'])->find($id)->toArray();

        $unit_users = UnitUser::where('user_id',$data['resident_id'])->first();
        $unit = Unit::select('id','unit_no','building_id')->where('id',$unit_users->unit_id )->first();
        $building = Building::find($unit['building_id'] ?? 0);
        $log = WorkOrderLog::where('workorder_id',$data['id'])->where('status','UNLOCK')->orderBy('id','desc')->first();
        $pdf = PDF::loadView('workorder', ['data' => $data, 'unit' => $unit , 'building' => $building, 'log' => $log]);
        return $pdf->download('Workorder # '.$id.'.pdf');
        $strtotime = strtotime(now());
        $filePath = 'app/workorder-'.$strtotime.'.pdf';   
        $pdf->save(storage_path($filePath));

        // $subject = 'Workorder # '.$data['id'] .' - '.$request->subject;

        // sendEmail($request->description,$request->email,null,$subject,storage_path($filePath),false);
        // unlink(storage_path($filePath));
        return response()->json(['file_path' => $filePath]);
    }

    public function updateWorkOrderPriority($id,$priority){
        $record =  WorkOrder::find($id);
        $record->update(['priority'=>$priority]);
        
        LogsRepo::addWorkOrderPriorityLog($priority, $id);

        return $record;
    }

    public function getMaintenanceUsers(Request $request){
        
        $users = StaffSubRoles::select('users.*',DB::raw("CONCAT(firstname, ' ', lastname) AS name"))
                        ->join('users','users.id','=','users_sub_roles_buildings.user_id')->where('users_sub_roles_buildings.sub_role',$request->sub_role)->where('building_id',$request->building_id)
                        ->groupBy('users.id')
                        ->get();
        return $users;
    }
}
