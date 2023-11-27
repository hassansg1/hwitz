<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class LaundryMachine extends Model
{

	protected $table = 'laundrymachines';
	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
	protected $guarded = [];

	protected $fields = [];

	public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

		$this->fields = collect([

		    ['fieldname' => 'id', 'header' => 'Id', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],

		    // ['fieldname' => 'modelName', 'header' => 'Model', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'model'],
		    ['fieldname' => 'name', 'header' => 'Appliance Id', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],

		    ['fieldname' => 'cost', 'header' => 'Cost Per Cycle($)', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'ip_address', 'header' => 'IP Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'run_dur', 'header' => 'Run Duration(in sec)', 'level' => 1, 'type' => 'number', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'start_url', 'header' => 'Start Url', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'reset_url', 'header' => 'Reset Url', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'camera_url', 'header' => 'Camera Url', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'number_cycles', 'header' => 'No of cycles', 'level' => 1, 'type' => 'number', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'laundrymodel_id', 'header' => 'Laundry Model', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'machine_models'],
		    ['fieldname' => 'state', 'header' => 'Appliance status', 'level' => 1, 'type' => 'select', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'machine_state'],
		    ['fieldname' => 'cost_type', 'header' => 'Cost Type', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'cost_types'],
		    ['fieldname' => 'type', 'header' => 'Type', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'applience_types'],

		    ['fieldname' => 'laundry_id', 'header' => 'Laundry Room', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'laundries'],
		    ['fieldname' => 'laundryName', 'header' => 'Laundry', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
		    ['fieldname' => 'retries', 'header' => 'Retries', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'status', 'header' => 'Status', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],


		    ['fieldname' => 'createdBy', 'header' => 'Created By', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
		    ['fieldname' => 'created', 'header' => 'Created Date', 'level' => 1, 'type' => 'textfield', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
		    ['fieldname' => 'modifiedBy', 'header' => 'Modified By', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
		    ['fieldname' => 'modified', 'header' => 'Modified Date', 'level' => 1, 'type' => 'textfield', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
			]);

    }


	public function getFields($type)
	{
		$filtered = $this->fields->filter(function ($item) use ($type) { 
		    return $item[$type] == 1;
		});

		$filtered->all();

		return $filtered->toArray();
	}

	public function getValidationRules()
	{
		return [
		    'name' => 'required',
		    'laundry_id' => 'required',
		    'cost' => 'required',
		    'ip_address' => 'required',
		    'run_dur' => 'required',
		    'start_url' => 'required',
		    'reset_url' => 'required',
		    'number_cycles' => 'required',
		    'laundrymodel_id' => 'required',
		    'cost_type' => 'required',
		    'state' => 'required',
		    'type' => 'required',
		];
	}

    public function getListingData($bid, $lid)
    {
    	return $this::where('building_id', $bid)
    			->where('laundry_id', $lid)	
    			->orderBy('name', 'asc')
    			->paginate(\Config::get('constants.device_list_pagination'));
    }

    public function getDeviceName()
    {
    	return 'Laundry Machine';
    }

    public function getViewData($bid)
    {

    	$models = DB::table('laundrymodels')->select('name', 'id')->where('status', 1)->get();

    	$laundries = DB::table('laundries')->select('name', 'id')->where('building_id', $bid)->get();

    	$states = \Config::get('constants.laundry_aplliance_status');
    	$types = \Config::get('constants.laundry_cost_types');
    	$applience_types = \Config::get('constants.laundry_aplliance_type');

    	return array(
    		'machine_models' => $models->pluck('name', 'id'), 
    		'machine_state' => $states, 
    		'cost_types' => $types,
    		'applience_types' => $applience_types,
    		'laundries' => $laundries->pluck('name', 'id')
    		);
    }

    //////////////////////////////////////////////////////

    public function readinessTest($id){ 
        // TODO: logic for rediness here
        return 0;
    }
    
    public function getComboList($bid)
    {
        return $this::where('building_id', $bid)
                ->orderBy('name', 'asc')
                ->get()
                ->pluck('name', 'id');
    }

    public function getDevice($bid)
    {
        return $this::where('building_id', $bid)
                ->orderBy('name', 'asc')
                ->get();
    }

    public function getLaundryComboList($bid)
    {
        return DB::table('laundrymachines')->select('name', DB::raw("CONCAT(id,'-',laundry_id) as hybridId"))->where('building_id', $bid)->orderBy('name', 'asc')->get()->pluck('name', 'hybridId');
    }
        
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function laundryName()
    {
        return $this->belongsTo(LaundryRoom::class, 'laundry_id');
    }

	public function getNumberOfDaysInStateAttribute($machine_id)
    {
		$log = LaundryMachineStateLog::select('action_date')->where('machine_id',$machine_id)->orderBy('id','desc')->first();
        $days = 'N/A';
        
        if (isset($log['action_date'])){
            $ts1 = strtotime($log['action_date']);
            $ts2 = strtotime(date('Y-m-d'));

            $datediff = $ts2 - $ts1;

            $days = floor($datediff / (60 * 60 * 24));
        }


        return $days;
    }
}
