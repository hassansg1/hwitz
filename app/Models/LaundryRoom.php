<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Traits\LogsActivity;

class LaundryRoom extends Model {
	
	protected $table = 'laundries';
	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

	protected $appends = ['formatted_status'];

	protected $fields = [];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
	public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

		$this->fields = collect([

		    ['fieldname' => 'id', 'header' => 'Id', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
		    ['fieldname' => 'name', 'header' => 'Name', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'location', 'header' => 'Location Description', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'assets_ids[]', 'header' => 'Laundry room Access/Egress points', 'level' => 1, 'type' => 'multiselect', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'laundry_gates'],
		    ['fieldname' => 'mac_address', 'header' => 'Mac Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'ip_address', 'header' => 'IP Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'test_url', 'header' => 'Test Url', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 1],
                    ['fieldname' => 'notify_time', 'header' => 'Notify Time', 'level' => 1, 'type' => 'number', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'formatted_status', 'header' => 'Status', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
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
		    'name' 	=> 'required'
		];
	}

    public function getListingData($bid, $lid)
    {
    	return $this::where('building_id', $bid)
    			->orderBy('id', 'desc')
    			->paginate(\Config::get('constants.device_list_pagination'));
    }

    public function getDeviceName()
    {
    	return 'Laundry Room';
    }

    public function getViewData($bid)
    {

    	$models = DB::table('assets')->select('name', 'id')->where('assettype_id', \Config::get('constants.laundry_gate'));

    	if ($bid) {
    		$models->where('building_id', $bid);
    	}

    	$models = $models->get();

    	return array('laundry_gates' => $models->pluck('name', 'id'));
    }

    //////////////////////////////////////////////////////

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
        
    public function getFormattedStatusAttribute() 
    {	
    	$status = $this->attributes['status'] ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">In Active</span>';
    	return $status;
    }

    public function getMacAddressAttribute() 
    {	
    	return ($this->screens()->count()) ? $this->screens()->first()->mac_address : '';
    }

    public function getIpAddressAttribute() 
    {	
    	return ($this->screens()->count()) ? $this->screens()->first()->ip_address : '';
    }

    public function getAssetsIdsAttribute() 
    {	
    	return ( $this->laundaryAssets()->count()) ? $this->laundaryAssets()->pluck('asset_id')->toArray() : [];
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo('App\User', 'modified_by');
    }

    public function screens()
    {
        return $this->hasMany('App\Models\Devices\LaundryScreen', 'laundry_id');
    }

    public function laundaryAssets()
    {
        return $this->hasMany('App\Models\Devices\AssetLaundry', 'laundry_id');
    }

    public function assets()
    {
        return $this->belongsToMany('App\Models\Devices\Asset', 'assets_laundries', 'laundry_id', 'asset_id');
    }

    // public function modelName()
    // {
    //     return $this->belongsTo('App\Models\Devices\DeviceModel', 'model_id');
    // }
}
