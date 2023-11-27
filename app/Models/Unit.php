<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends Model
{
    use HasFactory;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $appends = ['physical', 'notify_of_entry', 'formatted_status', 'formatted_internet', 'formatted_telephone', 'formatted_cable', 'formatted_fob','formatted_internet_usage','formatted_internet_daily'];

    protected $fields = [];

    protected $guarded = [];

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
            ['fieldname' => 'building_id', 'header' => 'Building', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'buildings'],
            ['fieldname' => 'unit_no', 'header' => 'Unit #', 'level' => 1, 'type' => 'text', 'required' => 'required', 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'status', 'header' => 'Status', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'unittype_id', 'header' => 'Unit Type', 'level' => 1, 'type' => 'select', 'required' => 'required', 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'unitytpes'],
            ['fieldname' => 'is_physical', 'header' => 'Is Physical', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'physical', 'header' => 'Is Physical', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'unit_size', 'header' => 'Unit Size', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'rent_amt', 'header' => 'Rent Amount', 'level' => 1, 'type' => 'decimal', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'rent_program_id', 'header' => 'Rent Program', 'level' => 1, 'type' => 'select', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'rent_programs'],
            ['fieldname' => 'redirect_to_rp', 'header' => 'Redirect To RP', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'did_number', 'header' => 'DID Number', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'floor', 'header' => 'Floor', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'button', 'header' => 'Button', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'call1', 'header' => 'Call 1', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'group1', 'header' => 'Group 1', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'call2', 'header' => 'Call 2', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'group2', 'header' => 'Group 2', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'call3', 'header' => 'Call 3', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'rd_user', 'header' => 'RD User', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'rd_pass', 'header' => 'RD Pass', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'rd_enable', 'header' => 'RD Enable', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'did_enable', 'header' => 'DID Enable', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'notify_at_entry', 'header' => 'Notify of Entry', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'notify_of_entry', 'header' => 'Notify of Entry', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'formatted_status', 'header' => 'Status', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'internet', 'header' => 'Internet', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'formatted_internet', 'header' => 'Internet', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'telephone', 'header' => 'Telephone', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'formatted_telephone', 'header' => 'Telephone', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'cable', 'header' => 'Cable', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'formatted_cable', 'header' => 'Cable', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],
            ['fieldname' => 'fob', 'header' => 'FOB', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0],
            ['fieldname' => 'formatted_fob', 'header' => 'FOB', 'level' => 1, 'type' => 'checkbox', 'required' => false, 'list' => 1, 'save' => 0, 'view' => 1],

            ['fieldname' => 'intercom_num', 'header' => 'Intercom Number', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],
            ['fieldname' => 'intercom_pass', 'header' => 'Intercom Password', 'level' => 1, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 1],

            ['fieldname' => 'laundry_balance', 'header' => 'Laundry Balance', 'level' => 1, 'type' => 'decimal', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'rent_balance', 'header' => 'Rent Balance', 'level' => 1, 'type' => 'decimal', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'amenity_balance', 'header' => 'Amenity Balance', 'level' => 1, 'type' => 'decimal', 'required' => false, 'list' => 1, 'save' => 1, 'view' => 1],
            ['fieldname' => 'guarantor_user_id', 'header' => 'Guarantor', 'level' => 1, 'type' => 'select', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'primary_user'],
            ['fieldname' => 'co_guarantor_user_id', 'header' => 'Co Guarantor', 'level' => 1, 'type' => 'select', 'required' => false, 'list' => 0, 'save' => 1, 'view' => 0, 'data' => 'primary_user'],

            ['fieldname' => 'createdBy', 'header' => 'Created By', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
            ['fieldname' => 'created', 'header' => 'Created Date', 'level' => 1, 'type' => 'textfield', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
            ['fieldname' => 'modifiedBy', 'header' => 'Modified By', 'level' => 2, 'type' => 'text', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1, 'child_field' => 'name'],
            ['fieldname' => 'modified', 'header' => 'Modified Date', 'level' => 1, 'type' => 'textfield', 'required' => false, 'list' => 0, 'save' => 0, 'view' => 1],
        ]);

    }

    public function getValidationRules()
    {
        return [
            'unit_no' => 'required'
        ];
    }

    public function unitHistory()
    {
        return $this->hasMany(UnitHistory::class, 'unit_id');
    }

    public function getViewData($bid)
    {
        $users = DB::table('users')
            ->select(DB::raw('CONCAT(firstname, " ", lastname) AS name'), 'users.id')
            ->where('usertype_id', 13);

        $users->get();

        $unitytpes = DB::table('unittypes')->select('name', 'id')->get();
        $buildings = DB::table('buildings')->select('name', 'id')->get();
        $rent_programs = DB::table('rent_programs')->select('code', 'id')->get();

        return array(
            'unitytpes' => $unitytpes->pluck('name', 'id'),
            'buildings' => $buildings->pluck('name', 'id'),
            'rent_programs' => $rent_programs->pluck('code', 'id'),
            'primary_user' => $users->pluck('name', 'id')
        );

    }

    public function getListingData($bid, $lid)
    {
        return $this::where('building_id', $bid)
            ->orderBy('unit_sort', 'asc')
            ->paginate(\Config::get('constants.device_list_pagination'));
    }

    public function getDeviceName()
    {
        return 'Unit';
    }

    public function getFields($type)
    {
        $filtered = $this->fields->filter(function ($item) use ($type) {
            return $item[$type] == 1;
        });

        $filtered->all();

        return $filtered->toArray();
    }

    public function getComboList($bid)
    {
        return $this::where('building_id', $bid)
            ->select('unit_no', 'id')
            ->orderBy('is_physical', 'desc')
            ->orderBy('unit_no', 'asc')
            ->get()
            ->pluck('unit_no', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo('App\User', 'modified_by');
    }

    public function telephoneLogs()
    {
        return $this->hasMany(TelephoneLogs::class, 'did_number', 'did_number');
    }

    public function internetHourly()
    {
        return $this->hasMany(InternetHourly::class, 'unit_id');
    }

    public function getPhysicalAttribute()
    {
        $status = isset($this->attributes['is_physical']) ? '<span class="label label-success">Yes</span>' : '<span class="label label-warning">No</span>';
        return $status;
    }

    public function getNotifyOfEntryAttribute()
    {
        $status = isset($this->attributes['notify_at_entry']) ? '<span class="label label-success">Yes</span>' : '<span class="label label-warning">No</span>';
        return $status;
    }

    public function getFormattedStatusAttribute()
    {
        $status = isset($this->attributes['status']) ? '<span class="label label-success">On</span>' : '<span class="label label-warning">Off</span>';
        return $status;
    }

    public function getFormattedInternetAttribute()
    {
        $status = isset($this->attributes['internet']) ? '<span class="label label-success">On</span>' : '<span class="label label-warning">Off</span>';
        return $status;
    }


    public function getFormattedTelephoneAttribute()
    {
        $status = isset($this->attributes['telephone']) ? '<span class="label label-success">On</span>' : '<span class="label label-warning">Off</span>';
        return $status;
    }

    public function getFormattedCableAttribute()
    {
        $status = isset($this->attributes['cable']) ? '<span class="label label-success">On</span>' : '<span class="label label-warning">Off</span>';
        return $status;
    }


    public function getFormattedFobAttribute()
    {
        $status = isset($this->attributes['fob']) ? '<span class="label label-success">On</span>' : '<span class="label label-warning">Off</span>';
        return $status;
    }

    public function building()
    {
        return $this->belongsTo(Building::class);

    }

    public function addons()
    {
        return $this->hasMany('App\Models\AddonsUnits');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'units_users', 'unit_id', 'user_id');
    }

    public function macs()
    {
        return $this->hasMany(UnitMac::class,'unit_id');
    }

    public function guarantor()
    {
        return $this->belongsTo(User::class, 'guarantor_user_id');
    }

    public function buildingPackageAddons()
    {
        return $this->hasManyThrough(
            'App\Models\BuildingPackageAddon',
            'App\Models\AddonsUnits',
            'unit_id', // Foreign key on users table...
            'addons_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'addon_id' // Local key on users table...
        );
    }

    public function thirdPartyRent()
    {
        return $this->hasOne(ThirdPartyUnitRent::class, 'unit_id');
    }

    public function unitType()
    {
        return $this->belongsTo(UnitTypes::class, 'unittype_id');
    }

    public function test(){
        if (isset($user_info['Unit']['internet_usage'])) {
            $user_info['Unit']['internet_usage'] = $this->formatBytes($user_info['Unit']['internet_usage'], 1);
            if (isset($user_info['Unit']['internet_usage_devices']))
                $user_info['Unit']['internet_usage'] .= ' (' . $user_info['Unit']['internet_usage_devices'] . ')';
            $user_info['Unit']['internet_usage'] .= ' <span title="Last 30 days" alt="Last 30 days">MTD</span>';
        }

        if (isset($user_info['Unit']['internet_daily'])) {
            $user_info['Unit']['internet_daily'] = $this->formatBytes($user_info['Unit']['internet_daily'], 1);
            if (isset($user_info['Unit']['internet_daily_devices']))
                $user_info['Unit']['internet_daily'] .= ' (' . $user_info['Unit']['internet_daily_devices'] . ')';
            $user_info['Unit']['internet_daily'] .= ' <span title="Since Midnight" alt="Since Midnight">Daily</span>';
        }
    }

    public function getFormattedInternetUsageAttribute(){
        try{
            $internet_usage = $this->formatBytes($this->internet_usage, 1);
            if (isset($this->internet_usage_devices))
                $internet_usage .= ' (' . $this->internet_usage_devices . ')';
            $internet_usage .= ' <span title="Last 30 days" alt="Last 30 days">MTD</span>';
            return $internet_usage;
        }catch(Exception $e){
            return $this->internet_usage;
        }
    }

    public function getFormattedInternetDailyAttribute(){
        try{
            $internet_daily = $this->formatBytes($this->internet_daily, 1);
            if(isset($this->internet_daily_devices)){
                $internet_daily .= ' (' . $this->internet_daily_devices . ')';
            }
            $internet_daily.= ' <span title="Since Midnight" alt="Since Midnight">Daily</span>';
            return $internet_daily;
        }catch(Exception $e){
            return $this->internet_daily;
        }
    }
    private function formatBytes($bytes, $precision = 2)
    {

        if ($bytes) {
            $unit = [" B", " KB", " MB", " GB", " TB", " EB"];
            $exp = floor(log($bytes, 1024)) | 0;
            return round($bytes / (pow(1024, $exp)), $precision) . $unit[$exp];
        } else {
            return "0 B";
        }
    }
}
