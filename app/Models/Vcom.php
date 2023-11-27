<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Vcom extends Model
{

    protected $table = 'vcoms';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $appends = ['formatted_active'];

    protected $fields = [];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->fields = collect([

            ['fieldname' => 'id', 'header' => 'Id', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
            ['fieldname' => 'name', 'header' => 'Name', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'model_id', 'header' => 'Model', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'vcom_models'],
            ['fieldname' => 'modelName', 'header' => 'Model', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'model'],
            ['fieldname' => 'description', 'header' => 'Description', 'level' => 1, 'type' => 'textarea', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'mac_address', 'header' => 'Mac Address', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'ip_address', 'header' => 'IP Address', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'admin_user', 'header' => 'Admin User', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'admin_pass', 'header' => 'Admin Password', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'formatted_active', 'header' => 'Active', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'snmp_ro', 'header' => 'SNMP RO', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'snmp_rw', 'header' => 'SNMP RW', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
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
            'name' => 'required',
            'model_id' => 'required'
        ];
    }

    public function getListingData($bid, $lid)
    {
        return $this::where('building_id', $bid)
            ->orderBy('name', 'asc')
            ->paginate(\Config::get('constants.device_list_pagination'));
    }

    public function getDeviceName()
    {
        return 'Video Intercom';
    }

    public function getViewData($bid)
    {

        $models = DB::table('models')->select('model as name', 'id')->where('device_id', \Config::get('constants.DEVICE_VCOMS'))->get();

        return array('vcom_models' => $models->pluck('name', 'id'));
    }

    //////////////////////////////////////////////////////

    public function readinessTest($id)
    {
        // TODO: logic for rediness here
        return 0;
    }

    public function getComboList($bid)
    {
        return $this::where('building_id', $bid)
            ->orderBy('ip_address', 'asc')
            ->get()
            ->pluck('name', 'id');
    }

    public function getDevice($bid)
    {
        return $this::where('building_id', $bid)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getFormattedActiveAttribute()
    {
        $status = $this->attributes['active'] ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">In Active</span>';
        return $status;
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo('App\User', 'modified_by');
    }

    public function modelName()
    {
        return $this->belongsTo('App\Models\Devices\DeviceModel', 'model_id');
    }

    public function buildingName()
    {
        return $this->belongsTo('App\Models\Building', 'building_id');
    }
}
