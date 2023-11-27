<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Asset extends Model
{

    protected $table = 'assets';
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    protected $appends = ['formatted_status'];

    protected $guarded = [];

    protected $fields = [];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->fields = collect([

            ['fieldname' => 'id', 'header' => 'Id', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
            ['fieldname' => 'assettype_id', 'header' => 'Type', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'asset_types'],
            ['fieldname' => 'model_id', 'header' => 'Model', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'asset_models'],
            ['fieldname' => 'name', 'header' => 'Point of entry/egress Name', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'typeName', 'header' => 'Type', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
            ['fieldname' => 'description', 'header' => 'Description', 'level' => 1, 'type' => 'textarea', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'mac_address', 'header' => 'Mac Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'ip_address', 'header' => 'IP Address', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'brivo_id', 'header' => 'Brivo ID', 'level' => 1, 'type' => 'text', 'required' => 'false', 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'formatted_active', 'header' => 'Active', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1, 'data' => 'active'],
            ['fieldname' => 'active', 'header' => 'Active', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'createdBy', 'header' => 'Created By', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
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
            'assettype_id' => 'required',
            'mac_address' => 'required',
            'ip_address' => 'required'
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
        return 'Entry Controller';
    }

    public function getViewData($bid)
    {

        $types = DB::table('assettypes')->select('name', 'id')->get();
        $models = DB::table('models')->select('model as name', 'id')->where('device_id', \Config::get('constants.DEVICE_ASSETS'))->get();

        return array('asset_types' => $types->pluck('name', 'id'), 'asset_models' => $models->pluck('name', 'id'));
    }

    //////////////////////////////////////////////////////

    public function getComboList($bid)
    {
        return $this::where('building_id', $bid)
            ->orderBy('name', 'asc')
            ->get()
            ->pluck('name', 'id');
    }

    public function readinessTest($id)
    {
        // TODO: logic for rediness here
        return 0;
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

    public function getFormattedStatusAttribute()
    {
        $status = isset($this->attributes['status']) && $this->attributes['status'] ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">In Active</span>';
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

    public function modelName()
    {
        return $this->belongsTo('App\Models\Devices\DeviceModel', 'model_id');
    }

    public function typeName()
    {
        return $this->belongsTo('App\Models\Devices\AssetType', 'assettype_id');
    }

    public function screens()
    {
        return $this->belongsToMany('App\Models\Devices\LaundryRoom', 'assets_laundries', 'asset_id', 'laundry_id');
    }

    public function units()
    {
        return $this->hasMany(AssetUnit::class, 'asset_id');
    }

    public function schedule(){
        return $this->hasOne(Schedule::class,'id','schedule_id')->select('id','name');
    }
}
