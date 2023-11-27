<?php

namespace App\Http\Controllers;

use App\Models\ApplianceReservation;
use App\Models\Building;
use App\Models\Laundry;
use App\Models\LaundryBuildingOffer;
use App\Models\LaundryMachine;
use App\Models\LaundryMachineCopyProfileTemplate;
use App\Models\LaundryMachineCopyProfileTemplateSchedules;
use App\Models\LaundryMachineCopyProfileTemplateSchedulesExceptions;
use App\Models\LaundryMachineLog;
use App\Models\LaundryMachineSchedule;
use App\Models\LaundryMachineScheduleExceptions;
use App\Models\LaundryMachineState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkOrder;

class LaundryController extends Controller
{
    public function index(Request $request, $id){
        $laundries = Laundry::with(['washer','dryer'])->where('building_id',$id)->get();
        
        $machine_states = $this->get_machine_states();
        $available_washers_count = 0;
        $available_dryers_count = 0;
        $laundries->each(function ($laundry) use ($machine_states , $available_washers_count, $available_dryers_count){
            // dump($available_dryers_count);
            $laundry['available_washers_count'] = $laundry->washer->where('state',1)->count();
            $laundry['available_dryers_count'] = $laundry->dryer->where('state',1)->count();
            $laundry->washer->each(function($washer) use ($machine_states){
                // if($washer->state == 1) $available_washers_count = $available_washers_count+1;
                $washer['days_in_state'] = $washer->getNumberOfDaysInStateAttribute($washer->id);

                $state_label = '';
                if ($washer['is_both'] == 0 && $washer['state'] == 1)
                    $state_label = ': FOB Only';
                elseif ($washer['is_both'] == 1 && $washer['state'] == 1)
                    $state_label = ': FOB or Coin';
                $status_label = $machine_states[$washer['state']]['label'] . $state_label;

                $washer['status_label'] = $status_label;
                $washer['status_class'] = $machine_states[$washer['state']]['class'];

                $washer['action_item_state'] = $washer['state'];
                if (!empty($washer['state_before_out_of_order'])) {
                    $washer['action_item_state'] = $washer['state_before_out_of_order'];
                }
            });

            $laundry->dryer->each(function ($dryer) use ($machine_states){
                // if($dryer->state == 1) $available_dryers_count = $available_dryers_count + 1;

                $dryer['days_in_state'] = $dryer->getNumberOfDaysInStateAttribute($dryer->id);

                $state_label = '';
                if ($dryer['is_both'] == 0 && $dryer['state'] == 1)
                    $state_label = ': FOB Only';
                elseif ($dryer['is_both'] == 1 && $dryer['state'] == 1)
                    $state_label = ': FOB or Coin';
                $status_label = $machine_states[$dryer['state']]['label'] . $state_label;

                $dryer['status_label'] = $status_label;
                $dryer['status_class'] = $machine_states[$dryer['state']]['class'];

                $dryer['action_item_state'] = $dryer['state'];
                if (!empty($dryer['state_before_out_of_order'])) {
                    $dryer['action_item_state'] = $dryer['state_before_out_of_order'];
                }
            });
        });
        // dd($available_dryers_count);
        
        return response()->json(['laundries' => $laundries,'machine_states' => $machine_states,'available_washers_count' => $available_washers_count , 'available_dryers_count' => $available_dryers_count]);
    }

    protected function get_machine_states(){
        $machine_states = LaundryMachineState::select('state','class','short_description as label')->get();
        $data = array();

        foreach($machine_states as $state){
            $data[$state['state']] = array(
                'class' => $state['class'],
                'label' => $state['label']
            );
        }
        
        return $data;
    }

    public function showHistory(Request $request, $id){
        $history_data = LaundryMachineLog::where('building_id',session('building_id'))->where('laundrymachine_id',$id)->orderBy('id','desc')->limit(20)->get();
        return $history_data;
    }

    public function setNewState($appliance_id, $state, $isBoth){
        
        $machine_data = LaundryMachine::select('laundry_id','status','name')->where('id',$appliance_id)->first();
        if ($machine_data) {
            $laundry_id = $machine_data['laundry_id'];
        } else {
            $laundry_id = 0;
        }
        
        $update_data =array('state' => $state);

        LaundryMachine::where('id',$appliance_id)->update($update_data);



        $user_id = Auth::id();
        $log = array();
        $log['building_id'] = session('building_id') ?? 0;
        $log['laundry_id'] = $laundry_id;
        $log['laundrymachine_id'] = $appliance_id;
        $log['state'] = $state;
        $log['status'] = $machine_data['status'];
        $log['comment'] = '';
        $log['created'] = date('Y-m-d H:i:s');
        $log['created_by'] = $user_id;
        LaundryMachineLog::create($log);
        
        return 1;
    }

    public function laundry_change_status(Request $request, $id)
    {
        $building_id = session('building_id') ?? 0;
        $comment = $request->comment ?? '';
        $machine = LaundryMachine::find($id);

        $appliance_name = $machine->name;
        $appliance_state = $machine->state;
        $appliance_status = $machine->status;
        $is_both = $machine->is_both;
        
        if($request->workOrder){
            WorkOrder::create([
                'resident_id' => 0,
                'created_by' => Auth::id(),
                'subject' => $appliance_name .' - out of order',
                'description' => $appliance_name .' - out of order',
                'status_id' => 'Open',
                'submitted' => Carbon::now()->format('Y-m-d H:i:s'),
                'priority' => "High"
            ]);
        }
        $update_fields = [];
        $update_fields['state'] = 2;
        $update_fields['status'] = $appliance_status;
        $update_fields['modified_by'] = Auth::id();
        $update_fields['state_before_out_of_order'] = $appliance_state.','.$is_both;
            
        $machine->update($update_fields);
    
        $user_id = Auth::id();
        $log = array();
        $log['building_id'] = $building_id;
        $log['laundry_id'] = $machine->laundry_id;
        $log['laundrymachine_id'] = $machine->id;
        $log['state'] = $update_fields['state'];
        $log['status'] = 0;
        $log['comment'] = $comment;
        $log['created'] = date('Y-m-d H:i:s');
        $log['created_by'] = $user_id;
        LaundryMachineLog::create($log);
        
        return 1;
        
    }

    public function laundry_change_reservable($id,$value)
    {
        return LaundryMachine::where('id',$id)->update(['is_reservable' => $value]);
    }

    public function seeReservation($id){

        $today = Carbon::now();
        $building_id = session('building_id') ?? 0;
        $reservation_data = ApplianceReservation::where('building_id',$building_id)->where('laundrymachine_id',$id)
                            ->whereDate('start_date_time','>=',$today->startOfWeek()->format('Y-m-d'))
                            ->whereDate('start_date_time','<=',$today->endOfWeek()->format('Y-m-d'))
                            ->whereNotIn('status',[2,3])->get();

        return $reservation_data;
    }


    public function create_weekly_planner_html($building_id, $washer_dryer_id = null)
    {
        $building_id = session('building_id') ?? 0;
        $weekly_planner_html = '';
        $week_array = $this->create_week('Monday');
        $time_array = $this->create_timeArray();
        $cost_data = [];
        if ($washer_dryer_id) {
            // $this->loadModel('LaundrymachineSchedules');
            // $cost_data = $this->LaundrymachineSchedules->find('all', array('conditions' => array('building_id' => $building_id, 'laundrymachine_id' => $washer_dryer_id), 'fields' => array('*')));
            $cost_data = LaundryMachineSchedule::where('building_id',$building_id)->where('laundrymachine_id',$washer_dryer_id)->get();
        }
        // dd($washer_dryer_id);
        $weekly_planner_html = $this->createWeekUI($week_array, array('DAYNAME'));
        $weekly_planner_html .= '<table cellspacing="0" class="table table-bordered weekly-planner-table"><thead style="border-top: white !important;"><tr>';
        if (count($time_array)) {
            for ($j = 0; $j < count($time_array); $j++) {
                $weekly_planner_html .= '<th>' . $time_array[$j]['time'] . '</th>';
            }
        }
        $weekly_planner_html .= '</tr></thead><tbody>';
        if (count($week_array)) {
            for ($i = 0; $i < count($week_array); $i++) {
                $weekly_planner_html .= '<tr>';
                if (count($time_array)) {
                    for ($j = 0; $j < count($time_array); $j++) {
                        $td_block_id = $week_array[$i]['index'] . '-' . $time_array[$j]['time'];
                        if (count($cost_data)) {
                            for ($k = 0; $k < count($cost_data); $k++) {
                                $div = '';
                                $cost_id = $cost_data[$k]['id'];
                                $explode_start = explode(':', $cost_data[$k]['cost_start_time']);
                                $explode_end = explode(':', $cost_data[$k]['cost_end_time']);
                                if (($cost_data[$k]['week_day'] == $week_array[$i]['index']) && ($explode_start[0] == $time_array[$j]['fraction'])) {
                                    $from_time = strtotime($cost_data[$k]['cost_start_time']);
                                    $to_time = strtotime($cost_data[$k]['cost_end_time']);
                                    $minute_diff = round(abs($to_time - $from_time) / 60, 2);
                                    $minute_fraction_diff = round($minute_diff / 30, 2);
                                    if ($explode_start[1] > 0) {
                                        $leftspace = 'leftspace';
                                        if ($explode_start[1] == 15) {
                                            $leftspace = 'leftspace-15';
                                        } elseif ($explode_start[1] == 30) {
                                            $leftspace = 'leftspace-30';
                                        } elseif ($explode_start[1] == 45) {
                                            $leftspace = 'leftspace-45';
                                        }
                                        if ($explode_start[0] == 12) {
                                            $start_time_show = '12:' . $explode_start[1] . 'PM' . '<br />to<br />';
                                            $start_time_send = '12:' . $explode_start[1] . 'PM';
                                        } else {
                                            $start_time_show = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . ':' . $explode_start[1] . 'PM' . '<br />to<br />' : (($explode_start[0] == 0) ? '12:' . $explode_start[1] . 'AM' . '<br />to<br />' : ltrim($explode_start[0], '0') . ':' . $explode_start[1] . 'AM' . '<br />to<br />');
                                            $start_time_send = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . ':' . $explode_start[1] . 'PM' : (($explode_start[0] == 0) ? '12:' . $explode_start[1] . 'AM' : ltrim($explode_start[0], '0') . ':' . $explode_start[1] . 'AM');
                                        }
                                    } else {
                                        $leftspace = '';
                                        if ($explode_start[0] == 12) {
                                            $start_time_show = '12PM' . '<br />to<br />';
                                            $start_time_send = '12PM';
                                        } else {
                                            $start_time_show = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . 'PM' . '<br />to<br />' : (($explode_start[0] == 0) ? '12AM' . '<br />to<br />' : ltrim($explode_start[0], '0') . 'AM' . '<br />to<br />');
                                            $start_time_send = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . 'PM' : (($explode_start[0] == 0) ? '12AM' : ltrim($explode_start[0], '0') . 'AM');
                                        }
                                    }
                                    if ($explode_end[1] > 0) {
                                        if ($explode_end[0] == 12) {
                                            $end_time_show = '12:' . $explode_end[1] . 'PM';
                                            $end_time_send = '12:' . $explode_end[1] . 'PM';
                                        } else {
                                            $end_time_show = ($explode_end[0] > 12) ? ($explode_end[0] - 12) . ':' . $explode_end[1] . 'PM' : (($explode_end[0] == 0) ? '12:' . $explode_end[1] . 'AM' : ltrim($explode_end[0], '0') . ':' . $explode_end[1] . 'AM');
                                            $end_time_send = $end_time_show;
                                        }
                                    } else {
                                        if ($explode_end[0] == 12) {
                                            $end_time_show = '12PM';
                                            $end_time_send = '12PM';
                                        } else {
                                            $end_time_show = ($explode_end[0] > 12) ? ($explode_end[0] - 12) . 'PM' : ltrim($explode_end[0], '0') . 'AM';
                                            $end_time_send = $end_time_show;
                                        }
                                    }
                                    if ($cost_data[$k]['cost_amount'] && $cost_data[$k]['cost_amount'] != "0.00") {
                                        $price = '$' . $cost_data[$k]['cost_amount'];
                                        $price_send = $cost_data[$k]['cost_amount'];
                                    } else {
                                        $price = 'Free Service';
                                        $price_send = 0;
                                    }
                                    $d = ($minute_diff > 60) ? ($minute_diff * 0.001) + ($minute_fraction_diff * 1.904) : ($minute_fraction_diff * 1.904);
                                    $div = '<div id="draggable" class="' . $leftspace . '" style="width:' . $d . '%;" data-toggle="tooltip" data-placement="top" title="' . $start_time_show . $end_time_show . '<br />' . $price . '">
                                    		<p><span style="font-size:xx-small;">' . $start_time_show . $end_time_show . '</span><span>' . $price . '</span></p></div>';
                                    break;
                                }
                            }
                            if (!empty($div)) {
                                $weekly_planner_html .= '<td id="' . $td_block_id . '" style="cursor:pointer;" onclick="openCostConfigPopup(\'' . $td_block_id . '\',\'' . $week_array[$i]['dayname'] . '\',\'' . $start_time_send . '\',\'' . $end_time_send . '\',\'' . $price_send . '\',\'' . $cost_id . '\');">' . $div . '</td>';
                            } else {
                                $weekly_planner_html .= '<td id="' . $td_block_id . '" style="cursor:pointer;" onclick="openCostConfigPopup(\'' . $td_block_id . '\',\'' . $week_array[$i]['dayname'] . '\',\'\',\'\',\'\',\'\');">' . $div . '</td>';
                            }
                        } else {
                            $weekly_planner_html .= '<td id="' . $td_block_id . '" style="cursor:pointer;" onclick="openCostConfigPopup(\'' . $td_block_id . '\',\'' . $week_array[$i]['dayname'] . '\',\'\',\'\',\'\',\'\');"></td>';
                        }
                    }
                }
                $weekly_planner_html .= '</tr>';
            }
        }
        $weekly_planner_html .= '</tbody></table><script>$(function(){ $(\'[data-toggle="tooltip"]\').tooltip({html: true}); })</script>';
        return $weekly_planner_html;
    }

     private function create_week($start_week = 'CURRENT')
     {
         $week_array = array();
         if ($start_week == 'CURRENT') {
             for ($i = 0; $i < 7; $i++) {
                 if ($i == 0) {
                     $week_array[] = array('date' => date('Y-m-d'), 'dayname' => date('l'));
                 } else {
                     $date = date('Y-m-d', strtotime("+" . $i . " day", strtotime(date('Y-m-d'))));
                     $dayname = date('l', strtotime("+" . $i . " day", strtotime(date('Y-m-d'))));
                     $week_array[] = array('date' => $date, 'dayname' => $dayname);
                 }
             }
         } else {
            $weekDayData = array(array('index' => '1','name' => 'Monday'),
					 array('index' => '2','name' => 'Tuesday'),
					 array('index' => '3','name' => 'Wednesday'),
					 array('index' => '4','name' => 'Thursday'),
					 array('index' => '5','name' => 'Friday'),
					 array('index' => '6','name' => 'Saturday'),
					 array('index' => '7','name' => 'Sunday'));
             for ($i = 0; $i < count($weekDayData); $i++) {
                 $week_array[$i] = array('date' => '', 'dayname' => $weekDayData[$i]['name'], 'index' => $weekDayData[$i]['index']);
             }
             
         }
         return $week_array;
     }
     
     private function create_timeArray()
     {
         $time_array[] = array('time' => '12AM', 'fraction' => 00);
         for ($j = 1; $j <= 12; $j++) {
             $time_array[] = ($j == 12) ? array('time' => $j . 'PM', 'fraction' => 12) : array('time' => $j . 'AM', 'fraction' => $j);
         }
         for ($k = 1; $k <= 11; $k++) {
             $time_array[] = array('time' => $k . 'PM', 'fraction' => $k + 12);
         }
         return $time_array;
     }

     private function createWeekUI($week_array, $fields = array('ALL'))
     {
        //  $dateHelper = new DateHelper(new View());
         $return_html = '';
         $return_html = '<ul class="days">';
         if (count($week_array)) {
             for ($i = 0; $i < count($week_array); $i++) {
                 if (array_key_exists('0', $fields)) {
                     if ($fields[0] == 'ALL') {
                         $return_html .= '<li><p>' . Carbon::parse($week_array[$i]['date'])->format('m-d-Y') . '<br>' . $week_array[$i]['dayname'] . '</p></li>';
                     } else if ($fields[0] != 'ALL') {
                         $return_html .= '<li><p>';
                         if ($fields[0] == 'DATE') {
                             $return_html .= Carbon::parse($week_array[$i]['date'])->format('m-d-Y');
                         } else if ($fields[0] == 'DAYNAME') {
                             $return_html .= $week_array[$i]['dayname'];
                         }
                         if (array_key_exists('1', $fields)) {
                             if ($fields[1] == 'DATE') {
                                 $return_html .= '<br>' . Carbon::parse($week_array[$i]['date'])->format('m-d-Y');
                             } else if ($fields[1] == 'DAYNAME') {
                                 $return_html .= '<br>' . $week_array[$i]['dayname'];
                             }
                         }
                         $return_html .= '</p></li>';
                     }
                 }
             }
             $return_html .= '</ul>';
         }
         return $return_html;
     }

    public function saveApplianceProfileTemplate(Request $request){
        $request->validate([
            'template_name' => 'required',
            'default_resident_charge' => 'required',
            'reservation_charge' => 'required',
            'no_show_charge' => 'required',
            'retail_rate' => 'required',
            'id' => 'required'
        ]);
        
        $template = LaundryMachineCopyProfileTemplate::updateOrCreate(['id' => $request->id],[
            'template_name' => $request->template_name,
            'default_resident_charge' => $request->default_resident_charge,
            'reservation_charge' => $request->reservation_charge,
            'no_show_charge' => $request->no_show_charge,
            'retail_rate' => $request->retail_rate,
            'user_id' => Auth::id(),
            'status' => 1
        ]);

        // if($request->has('exceptionalDiscount') && count($request->exceptionalDiscount) > 0){
        //     foreach($request->exceptionalDiscount as $data){
        //         $start_date = Carbon::parse($data['start_date'])->format('Y-m-d H:i:s');
        //         $end_date = Carbon::parse($data['end_date'])->format('Y-m-d H:i:s');

        //         LaundryMachineCopyProfileTemplateSchedulesExceptions::create([
        //             'laundrymachine_copyprofile_template_id' => $template['id'],
        //             'start_date' => $start_date,
        //             'end_date' => $end_date,
        //             'free_service' => $data['discount_type'] == 'free' ? 1 : 0,
        //             'discount_cost' => $data['discount_type'] != 'free' ? $data['price'] : 0
        //         ]);
        //     }
        // }

        return 1;
    }

    public function loadApplianceProfileTemplateData(){
        return LaundryMachineCopyProfileTemplate::with(['exceptions' => function($q){
            $q->whereDate('end_date' , '>' , date('Y-m-d'));
        }])->where('user_id',Auth::id())->orderBy('id','desc')->get();
    }

    public function validateExceptionalDiscount(Request $request){
        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',
            'from_time' => 'required',
            'to_time' => 'required',
            'discount_type' => 'required',
        ]);
        $requestData = $request->all();
        $from_date_time = strtotime($requestData['from_date'] . " " . $requestData['from_time']);
        $to_date_time = strtotime($requestData['to_date'] . " " . $requestData['to_time']);

        if ($to_date_time < $from_date_time || ($to_date_time - $from_date_time) <= 0) {
            $message = 'To Date & Time is always greater than From Date & Time!';
            return response()->json(['status' => 'error' , 'message' => $message]);
        }

        $data['start_date'] = Carbon::parse($requestData['from_date'] . " " . $requestData['from_time'])->format(config('app.PHP_DATE_TIME_FORMAT'));
        $data['end_date'] = Carbon::parse($requestData['to_date'] . " " . $requestData['to_time'])->format(config('app.PHP_DATE_TIME_FORMAT'));
        $data['price'] = $request->discount_type == 'cost' ? $request->discount_cost : "Free";
        $data['discount_type'] = $request->discount_type;

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getAdvertisements(){
        $building_id = session('building_id') ?? 0;

        return LaundryBuildingOffer::where('building_id',$building_id)->orderBy('id','desc')->get();
    }

    public function saveAdvertisement(Request $request){
        $request->validate([
            'offer_name' => 'required',
            'offer_num' => 'required',
            'offer_num_times' => 'required'
        ]);
        if($request->id){
            $record = LaundryBuildingOffer::find($request->id);
            return $record->update([
                'offer_name' => $request->offer_name,
                'offer_num' => $request->offer_num,
                'offer_num_times' => $request->offer_num_times,
                'building_id' => session('building_id') ?? 0,
                'modified_by' => Auth::id(),
                'bimage' => $request->image ? $request->image : ''
            ]);
        }
        return LaundryBuildingOffer::create([
            'offer_name' => $request->offer_name,
            'offer_num' => $request->offer_num,
            'offer_num_times' => $request->offer_num_times,
            'building_id' => session('building_id') ?? 0,
            'created_by' => Auth::id(),
            'modified_by' => Auth::id(),
            'bimage' => $request->image ? $request->image : ''
        ]);
    }

    public function saveLaundryName(Request $request){
        $request->validate([
            'template_name' => 'required'
        ]);
        return LaundryMachineCopyProfileTemplate::create([
            'template_name' => $request->template_name,
            'user_id' => Auth::id(),
            'status' => 1
        ]);

    }

    public function laundry_copyprofile_template_edit($template_id, $show_switcher=false)
    {
        
        $building_id = session('building_id') ?? '';
        
        $template_data = LaundryMachineCopyProfileTemplate::select('template_name')->find($template_id);
        $template_name = $template_data['template_name'];
        
        
        $building_data = Building::select('name','user_id')->where('id',$building_id)->first();
        $building_name = $building_data['name'];
        
        
        $weekly_planner_html = $this->create_weekly_planner_html_cp($template_id);

        $template_data = null;
        if($show_switcher){
            if (is_array($building_data) && count($building_data) > 0) {
                $user_id = $building_data['user_id'];
            } else {
                $user_id = 0;
            }
            
            $template_data = LaundryMachineCopyProfileTemplate::where('user_id',$user_id)->get();
        }

        return response()->json([
            'template_data' => $template_data, 'building_id' => $building_id, 'building_name' => $building_name, 'template_id' => $template_id,
            'weekly_planner_html' => $weekly_planner_html, 'template_name' => $template_name
        ]);
    }
    private function create_weekly_planner_html_cp($template_id)
    {
        $weekly_planner_html = '';
        $week_array = $this->create_week('Monday');
        $time_array = $this->create_timeArray();
        if ($template_id) {
            $cost_data = LaundryMachineCopyProfileTemplateSchedules::where('laundrymachine_copyprofile_template_id',$template_id)->get();
        }
        $weekly_planner_html = $this->createWeekUI($week_array, array('DAYNAME'));
        $weekly_planner_html .= '<table cellspacing="0" class="table table-bordered weekly-planner-table"><thead><tr>';
        if (count($time_array)) {
            for ($j = 0; $j < count($time_array); $j++) {
                $weekly_planner_html .= '<th>' . $time_array[$j]['time'] . '</th>';
            }
        }
        $weekly_planner_html .= '</tr></thead><tbody>';
        if (count($week_array)) {
            for ($i = 0; $i < count($week_array); $i++) {
                $weekly_planner_html .= '<tr>';
                if (count($time_array)) {
                    
                    for ($j = 0; $j < count($time_array); $j++) {
                        $td_block_id = $week_array[$i]['index'] . '-' . $time_array[$j]['time'];
                        if (count($cost_data)) {
                            for ($k = 0; $k < count($cost_data); $k++) {
                                $div = '';
                                $cost_id = $cost_data[$k]['id'];
                                $explode_start = explode(':', $cost_data[$k]['cost_start_time']);
                                $explode_end = explode(':', $cost_data[$k]['cost_end_time']);
                                if (($cost_data[$k]['week_day'] == $week_array[$i]['index']) && ($explode_start[0] == $time_array[$j]['fraction'])) {
                                    $from_time = strtotime($cost_data[$k]['cost_start_time']);
                                    $to_time = strtotime($cost_data[$k]['cost_end_time']);
                                    $minute_diff = round(abs($to_time - $from_time) / 60, 2);
                                    $minute_fraction_diff = round($minute_diff / 30, 2);
                                    
                                    if ($explode_start[1] > 0) {
                                        $leftspace = 'leftspace';
                                        if ($explode_start[1] == 15) {
                                            $leftspace = 'leftspace-15';
                                        } elseif ($explode_start[1] == 30) {
                                            $leftspace = 'leftspace-30';
                                        } elseif ($explode_start[1] == 45) {
                                            $leftspace = 'leftspace-45';
                                        }
                                        if ($explode_start[0] == 12) {
                                            $start_time_show = '12:' . $explode_start[1] . 'PM' . '<br />to<br />';
                                            $start_time_send = '12:' . $explode_start[1] . 'PM';
                                        } else {
                                            $start_time_show = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . ':' . $explode_start[1] . 'PM' . '<br />to<br />' : (($explode_start[0] == 0) ? '12:' . $explode_start[1] . 'AM' . '<br />to<br />' : ltrim($explode_start[0], '0') . ':' . $explode_start[1] . 'AM' . '<br />to<br />');
                                            $start_time_send = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . ':' . $explode_start[1] . 'PM' : (($explode_start[0] == 0) ? '12:' . $explode_start[1] . 'AM' : ltrim($explode_start[0], '0') . ':' . $explode_start[1] . 'AM');
                                        }
                                    } else {
                                        $leftspace = '';
                                        if ($explode_start[0] == 12) {
                                            $start_time_show = '12PM' . '<br />to<br />';
                                            $start_time_send = '12PM';
                                        } else {
                                            $start_time_show = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . 'PM' . '<br />to<br />' : (($explode_start[0] == 0) ? '12AM' . '<br />to<br />' : ltrim($explode_start[0], '0') . 'AM' . '<br />to<br />');
                                            $start_time_send = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . 'PM' : (($explode_start[0] == 0) ? '12AM' : ltrim($explode_start[0], '0') . 'AM');
                                        }
                                    }
                                    if ($explode_end[1] > 0) {
                                        if ($explode_end[0] == 12) {
                                            $end_time_show = '12:' . $explode_end[1] . 'PM';
                                            $end_time_send = '12:' . $explode_end[1] . 'PM';
                                        } else {
                                            $end_time_show = ($explode_end[0] > 12) ? ($explode_end[0] - 12) . ':' . $explode_end[1] . 'PM' : (($explode_end[0] == 0) ? '12:' . $explode_end[1] . 'AM' : ltrim($explode_end[0], '0') . ':' . $explode_end[1] . 'AM');
                                            $end_time_send = $end_time_show;
                                        }
                                    } else {
                                        if ($explode_end[0] == 12) {
                                            $end_time_show = '12PM';
                                            $end_time_send = '12PM';
                                        } else {
                                            $end_time_show = ($explode_end[0] > 12) ? ($explode_end[0] - 12) . 'PM' : ltrim($explode_end[0], '0') . 'AM';
                                            $end_time_send = $end_time_show;
                                        }
                                    }
                                    if ($cost_data[$k]['cost_amount'] && $cost_data[$k]['cost_amount'] != "0.00") {
                                        $price = '$' . $cost_data[$k]['cost_amount'];
                                        $price_send = $cost_data[$k]['cost_amount'];
                                    } else {
                                        $price = 'Free Service';
                                        $price_send = 0;
                                    }
                                    $d = ($minute_diff > 60) ? ($minute_diff * 0.001) + ($minute_fraction_diff * 1.904) : ($minute_fraction_diff * 1.904);
                                    $div = '<div id="draggable" class="' . $leftspace . '" style="width:' . $d . '%;" data-toggle="tooltip" data-placement="top" title="' . $start_time_show . $end_time_show . '<br />' . $price . '">
                                    		<p><span style="font-size:xx-small;">' . $start_time_show . $end_time_show . '</span><span>' . $price . '</span></p></div>';
                                    break;
                                }
                            }
                            if (!empty($div)) {
                                $weekly_planner_html .= '<td id="' . $td_block_id . '" style="cursor:pointer;" onclick="openCostConfigPopup(\'' . $td_block_id . '\',\'' . $week_array[$i]['dayname'] . '\',\'' . $start_time_send . '\',\'' . $end_time_send . '\',\'' . $price_send . '\',\'' . $cost_id . '\');" data-toggle="modal" data-target="#CostConfigModal">' . $div . '</td>';
                            } else {
                                $weekly_planner_html .= '<td id="' . $td_block_id . '" style="cursor:pointer;" onclick="openCostConfigPopup(\'' . $td_block_id . '\',\'' . $week_array[$i]['dayname'] . '\',\'\',\'\',\'\',\'\');" data-toggle="modal" data-target="#CostConfigModal">' . $div . '</td>';
                            }
                        } else {
                            $weekly_planner_html .= '<td id="' . $td_block_id . '" style="cursor:pointer;" onclick="openCostConfigPopup(\'' . $td_block_id . '\',\'' . $week_array[$i]['dayname'] . '\',\'\',\'\',\'\',\'\');" data-toggle="modal" data-target="#CostConfigModal"></td>';
                        }
                    }
                }
                $weekly_planner_html .= '</tr>';
            }
        }
        $weekly_planner_html .= '</tbody></table><script>$(function(){ $(\'[data-toggle="tooltip"]\').tooltip({html: true}); })</script>';
        return $weekly_planner_html;
    }

    public function laundry_validate_cost_other_weekdays_cp(Request $request)
    {
        $return_data = array();
        $building_id = session("building_id") ?? 0;

        $request = $request->all();
        $template_id = $request['template_id'];
        $current_week_day = $request['current_week_day'];
        $cost_start_time = date('H:i:s', (strtotime($request['cost_start_time']) + 1));
        $cost_end_time = date('H:i:s', (strtotime($request['cost_end_time'])));

        $existing_data = LaundryMachineCopyProfileTemplateSchedules::select('week_day')
                            ->where('laundrymachine_copyprofile_template_id',$template_id)
                            ->where('week_day','!=',$current_week_day)
                            ->where(function ($q1) use ($cost_start_time, $cost_end_time) {
                                $q1->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                    $query->where('cost_start_time', '>=', $cost_start_time)
                                            ->where('cost_start_time', '<=', $cost_end_time);
                                })
                                ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                    $query->where('cost_end_time', '>=', $cost_start_time)
                                            ->where('cost_end_time', '<=', $cost_end_time);
                                })
                                ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                    $query->where('cost_start_time', '<=', $cost_start_time)
                                            ->where('cost_end_time', '>=', $cost_start_time);
                                })
                                ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                    $query->where('cost_start_time', '<=', $cost_end_time)
                                            ->where('cost_end_time', '>=', $cost_end_time);
                                });
                            })
                            
                            ->orderBy('id','desc')
                            ->pluck('week_day')->toArray();
                            
        if (count($existing_data)) {
            $return_data['status'] = 1;
            $return_data['data'] = $existing_data;
        } else {
            $return_data['status'] = 0;
            $return_data['data'] = '';
        }
        return response()->json($return_data);
    }

    public function laundry_set_cost_data_cp(Request $request)
    {
        $return_data = array();
        $building_id = session('building_id') ?? 0;
        
        $arrData = $request->all();
        // dd($request->all());
        $arrData['cost_start_time'] = $arrData['cost_start_time'] . ':00';
        $arrData['cost_end_time'] = $arrData['cost_end_time'] . ':00';
        $from_time = strtotime($arrData['cost_start_time']);
        $to_time = strtotime($arrData['cost_end_time']);
        if ($to_time < $from_time && ($to_time - $from_time) < 0) {
            $return_data['status'] = 0;
            $return_data['data'] = '';
            $return_data['message'] = 'To Time is always greater than From Time!';
            $return_data['reload'] = 1;
        } else {
            $arrDataFinal = array();

            if ($arrData['id']) {
                $validation_data = $this->validate_cost_row_column_data_cp($arrData['id'], $arrData['template_id'], $arrData['week_day'], $arrData['cost_start_time'], $arrData['cost_end_time']);
            } else {
                $validation_data = $this->validate_cost_row_column_data_cp(0, $arrData['template_id'], $arrData['week_day'], $arrData['cost_start_time'], $arrData['cost_end_time']);
            }

            if (count($validation_data)) {
                $return_data['status'] = 0;
                $return_data['data'] = '';
                $return_data['message'] = 'This time range is not allowed as we have found existing data within this time range!';
                $return_data['reload'] = 1;
                $cost_data = $this->create_weekly_planner_html_cp($arrData['template_id']);
                $return_data['new_data'] = $cost_data;
            } else {
                $arrData['cost_start_time'] = date('H:i:s', (strtotime($arrData['cost_start_time']) + 1));
                if ($arrData['id']) {
                    $arrDataFinal['laundrymachine_copyprofile_template_id'] = $arrData['template_id'];
                    $arrDataFinal['week_day'] = $arrData['week_day'];
                    $arrDataFinal['cost_start_time'] = $arrData['cost_start_time'];
                    $arrDataFinal['cost_end_time'] = $arrData['cost_end_time'];
                    $arrDataFinal['cost_free_service'] = $arrData['cost_free_service'];
                    $arrDataFinal['cost_amount'] = ($arrData['cost_free_service'] == 0 && $arrData['cost_amount'] >= 0) ? $arrData['cost_amount'] : "0.00";
                } else {
                    $arrDataFinal[0]['laundrymachine_copyprofile_template_id'] = $arrData['template_id'];
                    $arrDataFinal[0]['week_day'] = $arrData['week_day'];
                    $arrDataFinal[0]['cost_start_time'] = $arrData['cost_start_time'];
                    $arrDataFinal[0]['cost_end_time'] = $arrData['cost_end_time'];
                    $arrDataFinal[0]['cost_free_service'] = $arrData['cost_free_service'];
                    $arrDataFinal[0]['cost_amount'] = ($arrData['cost_free_service'] == 0 && $arrData['cost_amount'] >= 0) ? $arrData['cost_amount'] : "0.00";

                    if (count($arrData['weekday_selection']) > 0) {
                        foreach ($arrData['weekday_selection'] as $key => $data) {
                            $k = $key + 1;
                            $arrDataFinal[$k]['laundrymachine_copyprofile_template_id'] = $arrData['template_id'];
                            $arrDataFinal[$k]['week_day'] = $data['index'];
                            $arrDataFinal[$k]['cost_start_time'] = $arrData['cost_start_time'];
                            $arrDataFinal[$k]['cost_end_time'] = $arrData['cost_end_time'];
                            $arrDataFinal[$k]['cost_free_service'] = $arrData['cost_free_service'];
                            $arrDataFinal[$k]['cost_amount'] = ($arrData['cost_free_service'] == 0 && $arrData['cost_amount'] >= 0) ? $arrData['cost_amount'] : "0.00";
                        }
                    }
                }

                // Call save or saveAll function according to 'id' field value
                if ($arrData['id']) {
                    $result = LaundryMachineCopyProfileTemplateSchedules::where('id',$arrData['id'])->update($arrDataFinal);
                } else {
                    $result = LaundryMachineCopyProfileTemplateSchedules::insert($arrDataFinal);
                }
                if ($result) {
                    $cost_data = $this->create_weekly_planner_html_cp($arrData['template_id']);
                    if (!empty($cost_data)) {
                        $return_data['status'] = 1;
                        $return_data['data'] = $cost_data;
                    } else {
                        $return_data['status'] = 0;
                        $return_data['data'] = '';
                    }
                } else {
                    $return_data['status'] = 0;
                    $return_data['data'] = '';
                }
            }
        }

        return response()->json($return_data);
    }

    private function validate_cost_row_column_data_cp($id, $template_id, $week_day, $cost_start_time, $cost_end_time)
    {
        $cost_start_time = date('H:i:s', (strtotime($cost_start_time) + 1));

        $existing_data = LaundryMachineCopyProfileTemplateSchedules::select('week_day')
                        ->where('laundrymachine_copyprofile_template_id',$template_id)
                        ->where('week_day','=',$week_day)
                        ->when($id, function($q) use ($id) {
                            $q->where('id','!=',$id);
                        })
                        ->where(function ($q1) use ($cost_start_time, $cost_end_time) {
                            $q1->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                $query->where('cost_start_time', '>=', $cost_start_time)
                                        ->where('cost_start_time', '<=', $cost_end_time);
                            })
                            ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                $query->where('cost_end_time', '>=', $cost_start_time)
                                        ->where('cost_end_time', '<=', $cost_end_time);
                            })
                            ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                $query->where('cost_start_time', '<=', $cost_start_time)
                                        ->where('cost_end_time', '>=', $cost_start_time);
                            })
                            ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                                $query->where('cost_start_time', '<=', $cost_end_time)
                                        ->where('cost_end_time', '>=', $cost_end_time);
                            });
                        })
                        ->get();
        return $existing_data;
    }

    public function laundry_set_discount_data_cp(Request $request)
    {
        $request->validate([
            'end_date' => 'required',
            'end_time' => 'required',
            'start_date' => 'required',
            'start_time' => 'required'
        ]);
        $return_data = array();
        $building_id = session('building_id') ?? 0;
        $request = $request->all();
        $from_date_time = strtotime($request['start_date'] . " " . $request['start_time'].':00');
        $to_date_time = strtotime($request['end_date'] . " " . $request['end_time'].':00');
        if ($to_date_time < $from_date_time || ($to_date_time - $from_date_time) <= 0) {
            $return_data['status'] = 0;
            $return_data['data'] = '';
            $return_data['message'] = 'To Date & Time is always greater than From Date & Time!';
        } else {
            if ($request['id']) {
                $validation_data = $this->validate_discount_data_cp($request['id'], $request['template_id'], $request['start_date'] . " " . $request['start_time'], $request['end_date'] . " " . $request['end_time']);
            } else {
                $validation_data = $this->validate_discount_data_cp(0, $request['template_id'], $request['start_date'] . " " . $request['start_time'], $request['end_date'] . " " . $request['end_time']);
            }
            if (count($validation_data)) {
                $return_data['status'] = 0;
                $return_data['data'] = '';
                $return_data['message'] = 'This date & time range is not allowed as we have found existing data within this date & time range!, Popup will be closed in 5 seconds';
                $return_data['reload'] = 1;
                // $discount_data = $this->LaundrymachineCopyprofileTemplateSchedulesExceptions->find(
                //         'all', array(
                //     'conditions' => array(
                //         'laundrymachine_copyprofile_template_id' => $request['template_id'],
                //         'DATE(end_date) >' => date('Y-m-d')),
                //     'fields' => array('*', 'converted_start_date', 'converted_end_date', 'converted_start_time', 'converted_end_time'),
                //     'order' => array('id' => 'desc')
                //         )
                // );
                $discount_data = LaundryMachineCopyProfileTemplateSchedulesExceptions::where('laundrymachine_copyprofile_template_id')
                                    ->whereDate('end_date','>',date('Y-m-d'))
                                    ->orderBy('id','desc')
                                    ->get();
                $return_data['new_data'] = $discount_data;
            } else {
                $id = $request['id'];
                $template_id = $request['template_id'];
                $start_date = $request['start_date'] . " " . $request['start_time'];
                $start_date = date('Y-m-d H:i:s', (strtotime($start_date) + 1));
                $end_date = $request['end_date'] . " " . $request['end_time'];
                $free_service = $request['free_service'];
                $discount_cost = $request['discount_cost'];

                $fields_array = array();
                $fields_array['laundrymachine_copyprofile_template_id'] = $template_id;
                $fields_array['start_date'] = $start_date;
                $fields_array['end_date'] = $end_date;
                $fields_array['free_service'] = $free_service;
                if ($request['discount_cost'] != "" && $request['discount_cost'] > 0) {
                    $fields_array['discount_cost'] = $discount_cost;
                    $fields_array['free_service'] = 0;
                }

                if ($id) {
                    $record_added = LaundryMachineCopyProfileTemplateSchedulesExceptions::find($id);
                    $record_added = $record_added->update($fields_array);
                } else {
                    $record_added = LaundryMachineCopyProfileTemplateSchedulesExceptions::create($fields_array);
                }

                if ($record_added) {

                    $discount_data = LaundryMachineCopyProfileTemplateSchedulesExceptions::where('laundrymachine_copyprofile_template_id',$fields_array['laundrymachine_copyprofile_template_id'])
                                        ->whereDate('end_date','>',date('Y-m-d'))
                                        ->orderBy('id','desc')
                                        ->get();
                    if (count($discount_data)) {
                        $return_data['status'] = 1;
                        $return_data['data'] = $discount_data;
                    } else {
                        $return_data['status'] = 1;
                        $return_data['data'] = array();
                    }
                } else {
                    $return_data['status'] = 0;
                    $return_data['data'] = '';
                }
            }
        }
        return response()->json($return_data);
    }

    private function validate_discount_data_cp($id, $template_id, $start_date_time, $end_date_time)
    {
        $start_date_time = date('Y-m-d H:i:s', (strtotime($start_date_time) + 1));
        $existing_data = LaundryMachineCopyProfileTemplateSchedulesExceptions::select('id')
                ->when($id, function($q) use ($id){
                    $q->where('id','!=',$id);
                })
                ->where('laundrymachine_copyprofile_template_id',$template_id)
                ->where(function ($q1) use ($start_date_time, $end_date_time) {
                    $q1->orWhere(function($query) use ($start_date_time, $end_date_time) {
                        $query->where('start_date', '>=', $start_date_time)
                                ->where('start_date', '<=', $end_date_time);
                    })
                    ->orWhere(function($query) use ($start_date_time, $end_date_time) {
                        $query->where('end_date', '>=', $start_date_time)
                                ->where('end_date', '<=', $end_date_time);
                    })
                    ->orWhere(function($query) use ($start_date_time, $end_date_time) {
                        $query->where('start_date', '<=', $start_date_time)
                                ->where('end_date', '>=', $start_date_time);
                    })
                    ->orWhere(function($query) use ($start_date_time, $end_date_time) {
                        $query->where('start_date', '<=', $end_date_time)
                                ->where('end_date', '>=', $end_date_time);
                    });
                })->get();
        return $existing_data;
    }

    public function deleteLaundryDiscount($id){
        return LaundryMachineCopyProfileTemplateSchedulesExceptions::where('id',$id)->delete();
    }

    public function laundry_washer_dryer_new_cost($room_selected, $washer_dryer_id)
    {
        $building_id = session('building_id') ?? '';

        $building_name = '';
        $building_data = Building::select('name','user_id')->where('id',$building_id)->first();
        // if($building_data) $building_name = $building_data['name'];

        // $laundry_room_name = '';
        // if ($room_selected) {
        //     $Options['conditions'] = array('Laundry.building_id' => $building_id);
        //     $this->Laundry->getLaundryRoom($Options, $room_selected);
        //     $rooms = $this->Laundry->rooms;
        //     $laundry_room_name = $rooms[$room_selected]['name'];
        // }

        // $washer_dryer_name = '';
        // $this->loadModel('Laundrymachine');
        // $matchine_data = $this->Laundrymachine->find('first', array('fields' => array('name', 'type'), 'conditions' => array('id' => $washer_dryer_id)));
        // $washer_dryer_name = $matchine_data['Laundrymachine']['name'];
        // $washer_dryer_type = $matchine_data['Laundrymachine']['type'];

        // get cost configuration details
        $weekly_planner_html = $this->create_weekly_planner_html($building_id, $washer_dryer_id);

        if ($building_data) {
            $user_id = $building_data['user_id'];
        } else {
            $user_id = 0;
        }

        // $this->loadModel('LaundrymachineCopyprofileTemplates');
        // $template_data = $this->LaundrymachineCopyprofileTemplates->find('all', array('conditions' => array('user_id' => $user_id), 'fields' => array('*')));
        $template_data = LaundryMachineCopyProfileTemplate::where('user_id',$user_id)->get();

        // $discount_data = $this->LaundrymachineSchedulesExceptions->find(
        //         'all', array(
        //     'conditions' => array(
        //         'building_id' => $building_id,
        //         'laundrymachine_id' => $appliance_id,
        //         'DATE(end_date) >' => date('Y-m-d')),
        //     'fields' => array('*', 'converted_start_date', 'converted_end_date', 'converted_start_time', 'converted_end_time'),
        //     'order' => array('id' => 'desc')
        //         )
        // );
        // $charges_data = $this->Laundrymachines->find(
        //         'all', array(
        //     'conditions' => array('id' => $appliance_id),
        //     'fields' => array('default_resident_charge', 'reservation_charge', 'retail_rate', 'no_show_charge')
        //         )
        // );
        $charges_data = LaundryMachine::where('id',$washer_dryer_id)->first();

        $discount_data = LaundryMachineScheduleExceptions::where('building_id',$building_id)->where('laundrymachine_id',$washer_dryer_id)
                            ->whereDate('end_date','>',date('Y-m-d'))
                            ->orderBy('id','desc')
                            ->get();

        return response()->json([
            'template_data' => $template_data,
            'weeklyPlannerHtml' => $weekly_planner_html,
            'building' => $building_data,
            'discount_data' => $discount_data,
            'charges_data' => $charges_data
        ]);
        // $test  = 'test';
        // $this->set(compact('building_id', 'building_name', 'room_selected', 'washer_dryer_id', 'washer_dryer_type', 'laundry_room_name', 'washer_dryer_name', 'weekly_planner_html', 'template_data', 'test'));
    }
    public function laundry_save_all_charges(Request $request)
    {

        $request->validate([
            'appliance_id' => 'required',
            'default_resident_charge' => 'required',
            'reservation_charge' => 'required',
            'retail_rate' => 'required',
            'no_show_charge' => 'required',
        ]);

        // dd($request->all());
        $return_data = array();
        $request = $request->all();
        $appliance_id = $request['appliance_id'];
        $default_resident_charge = $request['default_resident_charge'];
        $reservation_charge = $request['reservation_charge'];
        $retail_rate = $request['retail_rate'];
        $no_show_charge = $request['no_show_charge'];
        
        $fields_array = array();

        $fields_array['default_resident_charge'] = $default_resident_charge;
        $fields_array['reservation_charge'] = $reservation_charge;
        $fields_array['retail_rate'] = $retail_rate;
        $fields_array['no_show_charge'] = $no_show_charge;
        if ($appliance_id) {
            $record = LaundryMachine::where('id',$appliance_id)->update($fields_array);
        }else{
            $record = LaundryMachine::create($fields_array);
        }
        if ($record) {
            $return_data['status'] = 1;
            $return_data['data'] = 'SUCCESS';
        } else {
            $return_data['status'] = 0;
            $return_data['data'] = '';
        }
        return response()->json($return_data);
    }

    public function laundry_set_cost_data(Request $request)
    {
        $return_data = array();
        $building_id = session('building_id') ?? '';
        $arrData = $request->all();
        $arrData['cost_start_time'] = $arrData['cost_start_time'].":00";
        $arrData['cost_end_time'] = $arrData['cost_end_time'].":00";
        $from_time = strtotime($arrData['cost_start_time']);
        $to_time = strtotime($arrData['cost_end_time']);
        if ($to_time < $from_time && ($to_time - $from_time) < 0) {
            $return_data['status'] = 0;
            $return_data['data'] = '';
            $return_data['message'] = 'To Time is always greater than From Time!';
            $return_data['reload'] = 1;
        } else {
            // $this->loadModel('LaundrymachineSchedules');
            $arrDataFinal = array();

            if ($arrData['id']) {
                $validation_data = $this->validate_cost_row_column_data($arrData['id'], $building_id, $arrData['laundry_id'], $arrData['appliance_id'], $arrData['week_day'], $arrData['cost_start_time'], $arrData['cost_end_time']);
            } else {

                //$validation_data = $this->validate_cost_row_column_data(0,$building_id,$arrData['laundry_id'],$arrData['appliance_id'],0,$arrData['cost_start_time'],$arrData['cost_end_time']);
                $validation_data = $this->validate_cost_row_column_data(0, $building_id, $arrData['laundry_id'], $arrData['appliance_id'], $arrData['week_day'], $arrData['cost_start_time'], $arrData['cost_end_time']);
            }

            //if(count($validation_data)){
            if (count($validation_data)) {
                $return_data['status'] = 0;
                $return_data['data'] = '';
                $return_data['message'] = 'This time range is not allowed as we have found existing data within this time range!, Popup will be closed in 5 seconds';
                $return_data['reload'] = 1;
                $cost_data = $this->create_weekly_planner_html($building_id, $arrData['appliance_id']);
                $return_data['new_data'] = $cost_data;
            } else {
                $arrData['cost_start_time'] = date('H:i:s', (strtotime($arrData['cost_start_time']) + 1));
                if ($arrData['id']) {
                    $arrDataFinal['building_id'] = $building_id;
                    $arrDataFinal['laundry_id'] = $arrData['laundry_id'];
                    $arrDataFinal['laundrymachine_id'] = $arrData['appliance_id'];
                    $arrDataFinal['week_day'] = $arrData['week_day'];
                    $arrDataFinal['cost_start_time'] = $arrData['cost_start_time'];
                    $arrDataFinal['cost_end_time'] = $arrData['cost_end_time'];
                    $arrDataFinal['cost_free_service'] = $arrData['cost_free_service'];
                    $arrDataFinal['cost_amount'] = ($arrData['cost_free_service'] == 0 && $arrData['cost_amount'] >= 0) ? $arrData['cost_amount'] : "0.00";
                } else {
                    $arrDataFinal[0]['building_id'] = $building_id;
                    $arrDataFinal[0]['laundry_id'] = $arrData['laundry_id'];
                    $arrDataFinal[0]['laundrymachine_id'] = $arrData['appliance_id'];
                    $arrDataFinal[0]['week_day'] = $arrData['week_day'];
                    $arrDataFinal[0]['cost_start_time'] = $arrData['cost_start_time'];
                    $arrDataFinal[0]['cost_end_time'] = $arrData['cost_end_time'];
                    $arrDataFinal[0]['cost_free_service'] = $arrData['cost_free_service'];
                    $arrDataFinal[0]['cost_amount'] = ($arrData['cost_free_service'] == 0 && $arrData['cost_amount'] >= 0) ? $arrData['cost_amount'] : "0.00";

                    if (count($arrData['weekday_selection']) > 0) {
                        foreach ($arrData['weekday_selection'] as $key => $data) {
                            $k = $key + 1;
                            $arrDataFinal[$k]['building_id'] = $building_id;
                            $arrDataFinal[$k]['laundry_id'] = $arrData['laundry_id'];
                            $arrDataFinal[$k]['laundrymachine_id'] = $arrData['appliance_id'];
                            $arrDataFinal[$k]['week_day'] = $data['index'];
                            $arrDataFinal[$k]['cost_start_time'] = $arrData['cost_start_time'];
                            $arrDataFinal[$k]['cost_end_time'] = $arrData['cost_end_time'];
                            $arrDataFinal[$k]['cost_free_service'] = $arrData['cost_free_service'];
                            $arrDataFinal[$k]['cost_amount'] = ($arrData['cost_free_service'] == 0 && $arrData['cost_amount'] >= 0) ? $arrData['cost_amount'] : "0.00";
                        }
                    }
                }
                
                // Call save or saveAll function according to 'id' field value
                if ($arrData['id']) {
                    // $this->LaundrymachineSchedules->id = $arrData['id'];
                    // $result = $this->LaundrymachineSchedules->save($arrDataFinal);
                    $result = LaundryMachineSchedule::where('id',$arrData['id'])->update($arrDataFinal);
                } else {
                    // $result = $this->LaundrymachineSchedules->saveAll($arrDataFinal);
                    $result = LaundryMachineSchedule::insert($arrDataFinal);
                }
                if ($result) {
                    /* $cost_data = $this->LaundrymachineSchedules->find(
                        'all',array(
                        'conditions' => array(
                        'building_id' => $building_id,
                        'laundrymachine_id' => $arrData['appliance_id']),
                        'fields' => array('*'),
                        'order' => array('id' => 'desc')
                        )
                        ); */
                    $cost_data = $this->create_weekly_planner_html($building_id, $arrData['appliance_id']);
                    //if(count($cost_data)){
                    if (!empty($cost_data)) {
                        $return_data['status'] = 1;
                        $return_data['data'] = $cost_data;
                    } else {
                        $return_data['status'] = 0;
                        $return_data['data'] = '';
                    }
                } else {
                    $return_data['status'] = 0;
                    $return_data['data'] = '';
                }
            }
        }
        return response()->json($return_data);
    }

    private function validate_cost_row_column_data($id, $building_id, $laundry_id, $appliance_id, $week_day, $cost_start_time, $cost_end_time)
    {
        $cost_start_time = date('H:i:s', (strtotime($cost_start_time) + 1));
        // $this->loadModel('LaundrymachineSchedules');

        // $arrConditions = array();
        // $arrConditions[] = array('building_id' => $building_id);
        // $arrConditions[] = array('laundry_id' => $laundry_id);
        // $arrConditions[] = array('laundrymachine_id' => $appliance_id);

        // if ($id)
        //     $arrConditions[] = array('id !=' => $id);

        // if ($week_day)
        //     $arrConditions[] = array('week_day =' => $week_day);

            $existing_data = LaundryMachineSchedule::select('week_day')
                ->when($id, function($q) use($id){
                    $q->where('id','!=',$id);
                })
                ->where('laundrymachine_id',$appliance_id)
                ->where('laundry_id',$laundry_id)
                ->where('building_id',$building_id)
                ->when($week_day, function($q) use ($week_day){
                    $q->where('week_day','=',$week_day);
                })
                ->where(function ($q1) use ($cost_start_time, $cost_end_time) {
                    $q1->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                        $query->where('cost_start_time', '>=', $cost_start_time)
                                ->where('cost_start_time', '<=', $cost_end_time);
                    })
                    ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                        $query->where('cost_end_time', '>=', $cost_start_time)
                                ->where('cost_end_time', '<=', $cost_end_time);
                    })
                    ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                        $query->where('cost_start_time', '<=', $cost_start_time)
                                ->where('cost_end_time', '>=', $cost_start_time);
                    })
                    ->orWhere(function($query) use ($cost_start_time, $cost_end_time) {
                        $query->where('cost_start_time', '<=', $cost_end_time)
                                ->where('cost_end_time', '>=', $cost_end_time);
                    });
                })
                ->orderBy('id','desc')
                ->pluck('week_day')->toArray();
        // $existing_data = $this->LaundrymachineSchedules->find(
        //         'all', array(
        //     'conditions' => array(
        //         $arrConditions,
        //         'OR' => array(
        //             array(
        //                 'cost_start_time >=' => $cost_start_time,
        //                 'cost_start_time <=' => $cost_end_time
        //             ),
        //             array(
        //                 'cost_end_time >=' => $cost_start_time,
        //                 'cost_end_time <=' => $cost_end_time
        //             ),
        //             array(
        //                 'cost_start_time <=' => $cost_start_time,
        //                 'cost_end_time >=' => $cost_start_time
        //             ),
        //             array(
        //                 'cost_start_time <=' => $cost_end_time,
        //                 'cost_end_time >=' => $cost_end_time
        //             )
        //         )
        //     ),
        //     'fields' => array(
        //         //'id',
        //         'week_day',
        //     //'cost_start_time',
        //     //'cost_end_time'
        //     )
        //         )
        // );
        return $existing_data;
    }

    public function laundry_set_discount_data(Request $request)
    {
        $request->validate([
            'end_date' => 'required',
            'end_time' => 'required',
            'start_date' => 'required',
            'start_time' => 'required'
        ]);
        $return_data = array();
        $building_id = session('building_id') ?? 0;
        $request = $request->all();
        // echo "<pre>"; print_r($request); exit;
        $request['start_time'] = $request['start_time'].":00";
        $request['end_time'] = $request['end_time'].":00";
        $from_date_time = strtotime($request['start_date'] . " " . $request['start_time']);
        $to_date_time = strtotime($request['end_date'] . " " . $request['end_time']);
        if ($to_date_time < $from_date_time || ($to_date_time - $from_date_time) <= 0) {
            $return_data['status'] = 0;
            $return_data['data'] = '';
            $return_data['message'] = 'To Date & Time is always greater than From Date & Time!';
        } else {
            if ($request['id']) {
                $validation_data = $this->validate_discount_data($request['id'], $building_id, $request['laundry_id'], $request['appliance_id'], $request['start_date'] . " " . $request['start_time'], $request['end_date'] . " " . $request['end_time']);
            } else {
                $validation_data = $this->validate_discount_data(0, $building_id, $request['laundry_id'], $request['appliance_id'], $request['start_date'] . " " . $request['start_time'], $request['end_date'] . " " . $request['end_time']);
            }
            if (count($validation_data)) {
                $return_data['status'] = 0;
                $return_data['data'] = '';
                $return_data['message'] = 'This date & time range is not allowed as we have found existing data within this date & time range!, Popup will be closed in 5 seconds';
                $return_data['reload'] = 1;
                $discount_data = LaundryMachineScheduleExceptions::where('building_id',$building_id)
                                    ->where('laundrymachine_id',$request['appliance_id'])
                                    ->whereDate('end_date',date('Y-m-d'))
                                    ->orderBy('id','desc')
                                    ->get();
                $return_data['new_data'] = $discount_data;
            } else {
                $id = $request['id'];
                $appliance_id = $request['appliance_id'];
                $laundry_id = $request['laundry_id'];
                $start_date = $request['start_date'] . " " . $request['start_time'];
                $start_date = date('Y-m-d H:i:s', (strtotime($start_date) + 1));
                $end_date = $request['end_date'] . " " . $request['end_time'];
                //$start_time = $request['start_time'];
                //$end_time = $request['end_time'];
                $free_service = $request['free_service'];
                $discount_cost = $request['discount_cost'];


                $fields_array = array();

                $fields_array['laundrymachine_id'] = $appliance_id;
                $fields_array['laundry_id'] = $laundry_id;
                $fields_array['building_id'] = $building_id;
                $fields_array['start_date'] = $start_date;
                $fields_array['end_date'] = $end_date;
                //$fields_array['start_time'] = $start_time;
                //$fields_array['end_time'] = $end_time;
                $fields_array['free_service'] = $free_service;
                if ($fields_array['free_service'] == 0) {
                    $fields_array['discount_cost'] = $discount_cost;
                }

                if ($id) {
                    $record = LaundryMachineScheduleExceptions::where('id',$id)->update($fields_array);
                }else{
                    $record = LaundryMachineScheduleExceptions::insert($fields_array);
                }
                if ($record) {
                    $discount_data = LaundryMachineScheduleExceptions::where('building_id',$building_id)
                                    ->where('laundrymachine_id',$request['appliance_id'])
                                    ->whereDate('end_date','>',date('Y-m-d'))
                                    ->orderBy('id','desc')
                                    ->get();
                                    
                    $return_data['status'] = 1;
                    $return_data['data'] = $discount_data;
                } else {
                    $return_data['status'] = 0;
                    $return_data['data'] = '';
                }
            }
        }
        return response()->json($return_data);
    }

    private function validate_discount_data($id, $building_id, $laundry_id, $appliance_id, $start_date_time, $end_date_time)
    {
        $start_date_time = date('Y-m-d H:i:s', (strtotime($start_date_time) + 1));

        $existing_data = LaundryMachineScheduleExceptions::select('id')
            ->when($id, function($q) use ($id){
                $q->where('id','!=',$id);
            })
            ->where('laundrymachine_id',$appliance_id)
            ->where('laundry_id',$laundry_id)
            ->where('building_id',$building_id)
            ->where(function ($q1) use ($start_date_time, $end_date_time) {
                $q1->orWhere(function($query) use ($start_date_time, $end_date_time) {
                    $query->where('start_date', '>=', $start_date_time)
                            ->where('start_date', '<=', $end_date_time);
                })
                ->orWhere(function($query) use ($start_date_time, $end_date_time) {
                    $query->where('end_date', '>=', $start_date_time)
                            ->where('end_date', '<=', $end_date_time);
                })
                ->orWhere(function($query) use ($start_date_time, $end_date_time) {
                    $query->where('start_date', '<=', $start_date_time)
                            ->where('end_date', '>=', $start_date_time);
                })
                ->orWhere(function($query) use ($start_date_time, $end_date_time) {
                    $query->where('start_date', '<=', $end_date_time)
                            ->where('end_date', '>=', $end_date_time);
                });
            })->get();
        return $existing_data;
    }

    public function viewreservation($appliance_id, $appliance_type)
    {
        $return_html = '';

        $week_array = $this->create_week('CURRENT');
        $time_array = $this->create_timeArray();
        // $request = $request->all();
        // $appliance_id = $request['appliance_id'];
        // $appliance_type = $request['type'];
        
        if ($appliance_id && ($appliance_type == 'W' || $appliance_type == 'D')) {
            $building_id = session('building_id') ?? '';
            // $this->loadModel('AppliancesReservations');
            // $not_condition = array("status" => array(RES_MANUAL_CANCELLED, RES_SYSTEM_CANCELLED));
            //$reservation_data = $this->AppliancesReservations->find('all',array('conditions'=>array('building_id'=>$building_id,'laundrymachine_id'=>$appliance_id),'fields'=>array('*')));
            // $reservation_data = $this->AppliancesReservations->find('all', array(
            //     'conditions' => array(
            //         'building_id' => $building_id,
            //         'laundrymachine_id' => $appliance_id,
            //         'NOT' => $not_condition
            //     ),
            //     'fields' => array('*')
            //         )
            // );
            $reservation_data = ApplianceReservation::with(['user'])->where('building_id',$building_id)->where('laundrymachine_id',$appliance_id)
                            // ->whereDate('start_date_time','>=',$today->startOfWeek()->format('Y-m-d'))
                            // ->whereDate('start_date_time','<=',$today->endOfWeek()->format('Y-m-d'))
                            ->whereNotIn('status',[2,3])->get();
            $return_html = $this->createWeekUI($week_array, array('ALL'));

            $return_html .= '<table cellspacing="0" class="table table-bordered weekly-planner-table"><thead><tr>';
            if (count($time_array)) {
                for ($j = 0; $j < count($time_array); $j++) {
                    $return_html .= '<th>' . $time_array[$j]['time'] . '</th>';
                }
            }
            $return_html .= '</tr></thead><tbody>';
            if (count($week_array)) {
                for ($i = 0; $i < count($week_array); $i++) {
                    $return_html .= '<tr>';
                    if (count($time_array)) {
                        for ($j = 0; $j < count($time_array); $j++) {
                            if (count($reservation_data)) {
                                for ($k = 0; $k < count($reservation_data); $k++) {
                                    $div = '';
                                    // dd($reservation_data[$k]);
                                    $explode_start_date = explode(' ', $reservation_data[$k]['start_date_time']);
                                    $explode_end_date = explode(' ', $reservation_data[$k]['end_date_time']);
                                    $reserved_by = isset($reservation_data[$k]['user']) ? $reservation_data[$k]['user']['firstname'] . ' ' .$reservation_data[$k]['user']['lastname'] : '';
                                    // dd($reserved_by);
                                    //$explode_start = explode(':',$reservation_data[$k]['start_time']);
                                    //$explode_end = explode(':',$reservation_data[$k]['end_time']);
                                    if (count($explode_start_date)) {
                                        $explode_start = explode(':', $explode_start_date[1]);
                                    } else {
                                        $explode_start = array();
                                    }
                                    if (count($explode_end_date)) {
                                        $explode_end = explode(':', $explode_end_date[1]);
                                    } else {
                                        $explode_end = array();
                                    }
                                    if (isset($explode_start_date[0])) {
                                        //if(($reservation_data[$k]['date'] == $week_array[$i]['date']) && ($explode_start[0] == $time_array[$j]['fraction'])){
                                        if (($explode_start_date[0] == $week_array[$i]['date']) && ($explode_start[0] == $time_array[$j]['fraction'])) {
                                            //$from_time = strtotime($reservation_data[$k]['start_time']);
                                            //$to_time = strtotime($reservation_data[$k]['end_time']);
                                            $from_time = strtotime($explode_start_date[1]);
                                            $to_time = strtotime($explode_end_date[1]);
                                            $minute_diff = round(abs($to_time - $from_time) / 60, 2);
                                            $minute_fraction_diff = round($minute_diff / 30, 2);
                                            if ($explode_start[1] > 0) {
                                                $leftspace = 'leftspace';
                                                if ($explode_start[0] == 12) {
                                                    $start_time_show = '12:' . $explode_start[1] . 'PM' . '<br />to<br />';
                                                } else {
                                                    $start_time_show = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . ':' . $explode_start[1] . 'PM' . '<br />to<br />' : (($explode_start[0] == 0) ? '12:' . $explode_start[1] . 'AM' . '<br />to<br />' : ltrim($explode_start[0], '0') . ':' . $explode_start[1] . 'AM' . '<br />to<br />');
                                                }
                                            } else {
                                                $leftspace = '';
                                                if ($explode_start[0] == 12) {
                                                    $start_time_show = '12PM' . '<br />to<br />';
                                                } else {
                                                    $start_time_show = ($explode_start[0] > 12) ? ($explode_start[0] - 12) . 'PM' . '<br />to<br />' : (($explode_start[0] == 0) ? '12AM' . '<br />to<br />' : ltrim($explode_start[0], '0') . 'AM' . '<br />to<br />');
                                                }
                                            }
                                            if ($explode_end[1] > 0) {
                                                if ($explode_end[0] == 12) {
                                                    $end_time_show = '12:' . $explode_end[1] . 'PM';
                                                } else {
                                                    $end_time_show = ($explode_end[0] > 12) ? ($explode_end[0] - 12) . ':' . $explode_end[1] . 'PM' : (($explode_end[0] == 0) ? '12:' . $explode_end[1] . 'AM' : ltrim($explode_end[0], '0') . ':' . $explode_end[1] . 'AM');
                                                }
                                            } else {
                                                if ($explode_end[0] == 12) {
                                                    $end_time_show = '12PM';
                                                } else {
                                                    $end_time_show = ($explode_end[0] > 12) ? ($explode_end[0] - 12) . 'PM' : ltrim($explode_end[0], '0') . 'AM';
                                                }
                                            }
                                            $price = '$' . $reservation_data[$k]['amount'];
                                            $div = '<div id="draggable" class="' . $leftspace . '" style="width:' . ($minute_fraction_diff * 1.904) . '%;" data-toggle="tooltip" data-placement="top" title="' . $start_time_show . $end_time_show . '<br />' . $price . '<br /> Reserved By : ' . $reserved_by . '">
                                                        <p><span style="font-size:xx-small">' . $start_time_show . $end_time_show . '</span><span>' . $price . '</span></p></div>';
                                            break;
                                        }
                                    }
                                }
                                $return_html .= '<td>' . $div . '</td>';
                            } else {
                                $return_html .= '<td></td>';
                            }
                        }
                    }
                    $return_html .= '</tr>';
                }
            }
            $return_html .= '</tbody></table><script>$(function(){ $(\'[data-toggle="tooltip"]\').tooltip({html: true}); })</script>';
        }
        
        return $return_html;
    }

    

}
