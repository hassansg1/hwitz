<?php

namespace App\Repos;

use App\Models\User;
use App\Models\WorkOrderLog;
use App\Models\WorkOrderPriorityLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogsRepo
{
    public static function addUnitsLog($entity_id, $entity_name, $action_name, $notes = null, $building_id = null, $unit_id = null, $task = 0, $user_id = null, $triggeredBy = null)
    {

        if (is_array($notes) || is_object($notes)) {
            $notes = json_encode($notes); // encode notes
        }

        $unitLog = new \App\Models\UnitLogs();

        $unitLog->entity_id = $entity_id;
        $unitLog->unit_id = $unit_id;
        $unitLog->building_id = $building_id;
        $unitLog->entity_name = $entity_name;
        $unitLog->action_name = $action_name;
        $unitLog->triggered_at = date('Y-m-d H:i:s');
        $unitLog->triggered_by = $triggeredBy ?? (\Illuminate\Support\Facades\Auth::user()->id ?? 0);
        $unitLog->notes = $notes;
        $unitLog->origin_from_task = $task;
        $unitLog->ip_address = request()->ip();
        $unitLog->user_agent = getBrowser();


        if (!is_null($user_id)) {
            $unitLog->initiated_by = $user_id;
        }

        $unitLog->save();

    }

    public static function addSmsLog($to, $msg, $sid, $portal, $status, $user = null)
    {
        DB::table('sms_log')->insert([
            'user_id' => $user ? $user->id : null,
            'mobile' => $to,
            'type' => 'SMS',
            'is_incoming' => 1,
            'message' => $msg,
            'status' => $status,
            'sid' => $sid,
            'portal' => $portal,
            'timestamp' => Carbon::now(),
        ]);
    }

    public static function addCommunicationLog($type, $to, $msg, $subject = "", $attachment = '', $from = null, $portal = null)
    {
        if ($type == "email") {
            $from = $from ?? config('app.MAIL_USERNAME');
            $user = User::where('email', $to)->first();
        }
        if ($type == "sms") {
            $from = $from ?? config('app.TWILIO_FROM');
            $user = User::where('mobile', $to)->first();
        }

        $notes = [
            'attachments' => [$attachment]
        ];
        DB::table('communications')->insert([
            'from' => $from,
            'user_id' => $user->id ?? null,
            'unit_id' => $user->unit->id ?? null,
            'building_id' => $user->unit->building->id ?? null,
            'type' => $type,
            'subject' => $subject,
            'body' => $msg,
            'details' => json_encode($notes),
            'created_at' => Carbon::now()->format("m/d/Y h:i:s a"),
            'updated_at' => Carbon::now()->format("m/d/Y h:i:s a"),
        ]);
    }

    public static function addSystemLogs($unitId, $buildingId, $entityName, $actionName, $notes, $triggeredBy = null, $entity_id = 0)
    {
        if (is_array($notes)) $notes = json_encode($notes);

        DB::table('system_logs')->insert(
            [
                'building_id' => isset($buildingId) ? $buildingId : null,
                'unit_id' => isset($unitId) ? $unitId : null,
                'entity_id' => $entity_id,
                'entity_name' => isset($entityName) ? $entityName : null,
                'action_name' => isset($actionName) ? $actionName : null,
                'triggered_at' => date('Y-m-d H:i:s'),
                'triggered_by' => $triggeredBy ?? 0,
                'notes' => $notes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]
        );
    }

    public static function updateWorkOrderLog($id,$columns){
        $log = WorkOrderLog::where('workorder_id',$id)->first();
        if($log) {
            $log->update($columns);
        }
        else {
            $columns['workorder_id'] = $id;
            $columns['open_date'] = date('Y-m-d');
            WorkOrderLog::create($columns);
        }
    }

    public static function addWorkOrderPriorityLog($priority, $work_order_id){
        return WorkOrderPriorityLog::create([
            'priority' => $priority,
            'work_order_id' => $work_order_id,
            'modified_by' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
