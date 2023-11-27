<?php

namespace App\Services\Parsers;


use App\Models\AssetLog;
use App\Models\DoorLog;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class IntercomParser
{
    /**
     * @var Building
     */
    public $query;
    public $building;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        $this->query = $this->getBaseQuery($request);
        $this->applyBuildingFilter($request);
        $this->applyDateFilter($request);
        $this->applyEventTypeFilter($request);
        $data = $this->paginate($request);
        $data['data'] = $this->addFirstAndLast($data['data'], $request);
        return $data;

    }

    public function addFirstAndLast($logs, $request)
    {
        $logs = $logs->toArray();
        $next_item = '';
        if (count($logs) && $logs[count($logs) - 1]['event_data1'] != '*') {
            do {
                $currentLog = $logs[count($logs) - 1];
                $new_item = DoorLog::where('event_type', 'KeyPressed')
                    ->where('timestamp', '<=', $currentLog['timestamp'])
                    ->where('id', '<', $currentLog['id'])
                    ->orderby('id', 'desc')
                    ->first();

                if ($new_item) {
                    $new_item = $new_item->toArray();
                    $logs[] = $new_item;
                    $next_item = $logs[count($logs) - 1]['event_data1'];
                } else {
                    $next_item = '*';
                }

            } while ($next_item != '*');
        }
        $prev_item = '';
        $counter = 0;
        $currentLog = $logs[0] ?? '';
        if (count($logs) && $currentLog['event_data1'] != '*') {
            do {
                $new_item = DoorLog::where('event_type', 'KeyPressed')
                    ->where('timestamp', '>=', $currentLog['timestamp'])
                    ->where('id', '>', $currentLog['id'])
                    ->orderby('id', 'asc')
                    ->first();
                if ($new_item) {
                    $new_item = $new_item->toArray();
                    array_unshift($logs, $new_item);
                    $prev_item = $logs[0]['event_data1'];

                } else {
                    $prev_item = '*';
                }

            } while ($prev_item != '*');
        }

        $records = array();
        $code = '';
        $counter = 0;
        $keypress = date('Y-m-d H:i:s');
        for ($q = 0; $q < count($logs); $q++) {
            $counter++;

            $current_time = strtotime($logs[$q]['timestamp']);
            $next_time = strtotime(isset($logs[$q - 1]['timestamp']) ? $logs[$q - 1]['timestamp'] : date('Y-m-d H:i:s'));
            $time_diff = abs($current_time - $next_time);

            if ($logs[$q]['event_data1'] == '%1') {
                $data['phones'] = 'button';
                $data['code'] = '%1';
                $data['from'] = $logs[$q]['timestamp'];
                $data['unit'] = '';
                $data['user'] = '';
                $data['nine_astaric'] = '';
                $data['incoming_call'] = '';
                $data['legacy'] = '';
                $records[] = $data;

            } elseif (!in_array($logs[$q]['event_data1'], array('*', '#')) && ($time_diff <= 5)) {
                $code .= $logs[$q]['event_data1'];
            } elseif ((in_array($logs[$q]['event_data1'], array('*', '#')) || ($time_diff > 5)) && ($counter > 1) && ($code != '')) {
                $oldkeypress = $keypress;
                $next = DoorLog::
                where('timestamp', '>=', $logs[$q - 1]['timestamp'])
                    ->where('timestamp', '<', $keypress)
                    ->where('event_data1', 'outgoing')
                    ->where('event_data2', 'terminated')
                    ->orderby('timestamp', 'asc')
                    ->first();

                if ($next)
                    $next = $next->toArray();
                $keypress = $logs[$q - 1]['timestamp'];

                if (!isset($next['timestamp'])) {
                    $next['timestamp'] = $oldkeypress;
                }

                $data = array(
                    'code' => strrev($code) . '*',
                    'from' => $logs[$q - 1]['timestamp'],
                    'to' => isset($next['timestamp']) ? $next['timestamp'] : NULL
                );
                $code = '';
                if ($time_diff > 5 && !in_array($logs[$q]['event_data1'], array('*', '#'))) {
                    $code .= $logs[$q]['event_data1'];
                }

                $log = DoorLog::where('event_data2', 'connected')
                    ->where('timestamp', '>=', $logs[$q - 1]['timestamp']);

                $phonelogs = DoorLog::where('event_data2', 'connecting')
                    ->where('timestamp', '>=', $logs[$q - 1]['timestamp']);

                $conditions2 = array(
                    'AssetLog.timestamp >=' => $logs[$q - 1]['timestamp'],
                );

                if (isset($next['timestamp'])) {
                    $log = $log->where('timestamp', '<=', $next['timestamp']);
                    $phonelogs = $phonelogs->where('timestamp', '<=', $next['timestamp']);
                    $conditions2['AssetLog.timestamp <='] = $next['timestamp'];
                }
                if (isset($request->buildingId) && $request->buildingId != "") {
                    $log = $log->where('building_id', $request->buildingId);
                    $phonelogs = $phonelogs->where('building_id', $request->buildingId);
                }
                if (isset($request->door_id) && $request->door_id != "") {
                    $log = $log->where('door_id', $request->door_id);
                    $phonelogs = $phonelogs->where('door_id', $request->door_id);
                }

                $log = $log->first();
                if ($log) {
                    $log = $log->toArray();
                }

                $phone = isset($log['event_data3']) ? $log['event_data3'] : null;
                if (!is_null($phone) && strpos($phone, "@")) {
                    $phone = strstr($phone, ":");
                    $phone = substr($phone, 1, strlen($phone));
                    $phone = strstr($phone, "@", true);
                } elseif (!strpos($phone, "@")) {
                    $phone = substr($phone, 4, strlen($phone));
                }

                $data['phone'] = $phone;


                $phonelogs = $phonelogs->orderBy('timestamp', 'ASC')->get()->toArray();

                $data['phones'] = '';

                foreach ($phonelogs as $door) {

                    $phones = isset($door['event_data3']) ? $door['event_data3'] : null;
                    if (!is_null($phones) && strpos($phones, "@")) {
                        $phones = strstr($phones, ":");
                        $phones = substr($phones, 1, strlen($phones));
                        $phones = strstr($phones, "@", true);

                    } elseif (!strpos($phones, "@")) {
                        $phones = substr($phones, 4, strlen($phones));
                    }

                    $data['phones'] .= "<span class='" . ($phones == $phone ? 'green' : '') . "'>" . $phones . "</span><br />";

                }


                if ($phone && (strlen($phone) == 10)) {
                    $user = Unit::orWhere('call1', $phone)
                        ->orWhere('call2', $phone)
                        ->orWhere('call3', $phone)
                        ->first();
                    if ($user)
                        $user = $user->toArray();
                    $data['unit'] = isset($user['unit_no']) ? $user['unit_no'] : null;
                    $data['unit_id'] = isset($user['id']) ? $user['id'] : null;

                    $formatted_phone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);

                    $user = User::orWhere('mobile', $phone)
                        ->orWhere('mobile', $formatted_phone)
                        ->first();

                    if ($user)
                        $user = $user->toArray();

                    $data['user'] = isset($user['firstname']) ? $user['firstname'] . ' ' . $user['lastname'] : null;
                } elseif ((strlen($phone) > 1)) {
                    $data['unit'] = 'vcom';
                }

                /// check resident opened the door by 99

                $log = DoorLog::where('event_data1', 1)
                    ->where('event_data2', 1)
                    ->where('event_data3', 'dtmf')
                    ->where('building_id', $request->buildingId)
                    ->where('timestamp', '>=', $logs[$q - 1]['timestamp']);

                if (isset($request->door_id) && $request->door_id != "") {
                    $log = $log->where('door_id', $request->door_id);
                }

                if (isset($next['timestamp'])) {
                    $log = $log->where('timestamp', '<', $next['timestamp']);
                }

                $log = $log->orderBy('id', 'ASC')->get();
                $data['nine_astaric'] = count($log) ? true : false;

                // check was opened by incoming call

                $log = DoorLog::where('event_data1', 'incoming')
                    ->where('building_id', $request->buildingId)
                    ->where('event_type', 'CallStateChanged')
                    ->where('timestamp', '>=', $logs[$q - 1]['timestamp']);

                if (isset($request->door_id) && $request->door_id != "") {
                    $log = $log->where('door_id', $request->door_id);
                }

                if (isset($next['timestamp'])) {
                    $log = $log->where('timestamp', '<', $logs[$q - 1]['timestamp']);
                }

                $log = $log->orderBy('id', 'ASC')->get();
                $data['incoming_call'] = count($log) ? true : false;

                $data['legacy'] = false;
                if (isset($logs[$q])) {
                    //$conditions2['EntryLog.device'] = $logs[$q]['door_id'];
                    $log = AssetLog::where('assets_log.event_type', 4045)
                        ->where('entry_log.event_type', 18)
                        ->where('assets_log.timestamp', '>=', $logs[$q - 1]['timestamp'])
                        ->join('entry_log', 'entry_log.assets_log_id', '=', 'assets_log.id');

                    if (isset($next['timestamp'])) {
                        $log = $log->where('assets_log.timestamp', '<', $logs[$q - 1]['timestamp']);
                    }
                    $log = $log->get();
                    $data['legacy'] = count($log) ? true : false;
                }
                $records[] = $data;
            }
        }

        if (isset($request->unit_id) && $request->unit_id != "") {
            $records = array_filter($records, function ($var) use ($request) {
                return (isset($var['unit_id']) && ($request->unit_id == $var['unit_id']));
            });
        }

        return $records;
    }

    /**
     * @param $request
     * @return void
     */
    public function applyEventTypeFilter($request)
    {
        $this->query->where('event_type', 'KeyPressed');
    }

    /**
     * @param $request
     * @return void
     */
    public function applyBuildingFilter($request)
    {
        if (isset($request->buildingId) && $request->buildingId != "") {
            $this->query->where('building_id', $request->buildingId);
        }
    }

    /**
     * @param $request
     * @return void
     */
    public function applyDateFilter($request)
    {
        $dates = parseDate($request);
        $from = $dates["from"];
        $to = $dates["to"];

        if ($from && $to) {
            $this->query->where('timestamp', '>=', $from);
            $this->query->where('timestamp', '<=', $to);
        }
    }

    public function getBaseQuery($request = null)
    {
        return DoorLog::with('door', 'entryLog');
    }

    public function paginate($request)
    {
        $perPage = $request->totalItems ?? 100;
        $currentPage = $request->current_page;

        $this->query = $this->query->orderBy('id', 'desc');
        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $data;


        return $returnObj;
    }
}
