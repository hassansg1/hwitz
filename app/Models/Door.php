<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Door extends Model {
	
	protected $table = 'doors';
	const CREATED_AT = 'created';
	const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
	protected $appends = ['formatted_status', 'formatted_active'];

	protected $fields = [];

	public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

		$this->fields = collect([
		    ['fieldname' => 'id', 'header' => 'Id', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
		    ['fieldname' => 'name', 'header' => 'Name', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'mac_address', 'header' => 'Mac Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'ip_address', 'header' => 'IP Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'admin_user', 'header' => 'Admin User', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'admin_pass', 'header' => 'Admin Password', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
		    ['fieldname' => 'sn', 'header' => 'Serial Number', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
		    ['fieldname' => 'ext', 'header' => 'Phone Number', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
		    ['fieldname' => 'secret', 'header' => 'Phone Secret', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
		    ['fieldname' => 'domain', 'header' => 'Phone Domain', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
		    ['fieldname' => 'sip_port', 'header' => 'Phone Port', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
		    ['fieldname' => 'formatted_status', 'header' => 'Status', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
		    ['fieldname' => 'formatted_active', 'header' => 'Active', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
		    ['fieldname' => 'active', 'header' => 'Active', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
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
		    'name' 		 => 'required',
		    'mac_address'=> 'required',
		    'ip_address' => 'required',
		    'admin_user' => 'required',
		    'admin_pass' => 'required',
		];
	}

    public function getListingData($bid, $lid)
    {
    	return $this::where('building_id', $bid)
    			->orderBy('ip_address', 'asc')
    			->paginate(\Config::get('constants.device_list_pagination'));
    }

    public function getDeviceName()
    {
    	return 'Door Intercoms';
    }

    public function getViewData($bid)
    {
    	return array();
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
        
    public function getFormattedStatusAttribute() 
    {	
    	$status = $this->attributes['status'] ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">In Active</span>';
    	return $status;
    }

    public function getFormattedActiveAttribute() 
    {    	
    	$status = $this->attributes['active'] ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">In Active</span>';
      	return $status;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
