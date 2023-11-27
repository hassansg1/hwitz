<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemLog extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    const MODULE_RESIDENT = 'Resident';
    // RESIDENT ACTIONS
    const ACTION_RESIDENT_ADDED = 'ADDED';
    const ACTION_RESIDENT_MOVE = 'MOVED';
    const ACTION_RESIDENT_MOVE_OUT = 'MOVED OUT';
    const ACTION_RESIDENT_LINK_TO_OTHER_UNIT = 'LINKED TO OTHER UNIT';
    const ACTION_RESIDENT_UNLINK = 'UNLINKED';
    const ACTION_RESIDENT_MOVE_TO_OTHER_UNIT = 'MOVED TO OTHER UNIT';
    const ACTION_RESIDENT_FOB_ASSIGN = 'FOB ASSIGNED';
    const ACTION_RESIDENT_FOB_UNASSIGN = 'FOB UNASSIGNED';
    const ACTION_RESIDENT_FOB_TURNED_ON = 'FOB ON';
    const ACTION_RESIDENT_FOB_TURNED_OFF = 'FOB OFF';
    const ACTION_RESIDENT_FOBM_TURNED_ON = 'mFOB ON';
    const ACTION_RESIDENT_FOBM_TURNED_OFF = 'mFOB OFF';
    const ACTION_RESIDENT_MADE_PRIMARY = 'MADE PRIMARY';

    const MODULE_UNIT = 'Unit';
    const ACTION_UNIT_DISABLE_ALL_SERVICES = 'DISABLED ALL SERVICES';
    const ACTION_UNIT_DOOR_INTERCOM_UPDATE = 'DOOR INTERCOM UPDATED';
    const ACTION_UNIT_MOVEOUT_ALL = 'ALL RESIDENTS MOVED OUT';

    const MODULE_USER_STAFF = 'Staff';
    // STAFF ACTIONS
    const ACTION_STAFF_BUILDING_ACCESS_GRANTED = 'BUILDING ACCCESS GRANTED';
    const ACTION_STAFF_PASSWORD_CHANGED = 'PASSWORD CHANGED';
    const ACTION_STAFF_EMAIL_CHANGED = 'EMAIL CHANGED';
    const ACTION_STAFF_MOBILE_CHANGED = 'MOBILE CHANGED';

    // BUILDING ENTRANCE ACTIONS
    const ACTION_BUILDING_ENTRANCE_LOCKED = 'LOCKED';
    const ACTION_BUILDING_ENTRANCE_UNLOCKED = 'UNLOCKED';
    const ACTION_BUILDING_ENTRANCE_GRANTED = 'GRANTED';

    const MODULE_UNIT_SHUT_OFF = 'Unit Shut Off';
    const MODULE_NOTIFY_OF_ENTRY = 'Notify of Entry';
    const MODULE_INTERNET_SERVICE = 'Internet Service';
    const MODULE_TELEPHONE_SERVICE = 'Telephone Service';

    const MODULE_USER = 'User';
    const ACTION_USER_ASSIGN_GUARANTOR_ROLE = 'GUARANTOR ROLE ASSIGNED';
    const ACTION_USER_ASSIGN_COGUARANTOR_ROLE = 'COGUARANTOR ROLE ASSIGNED';
    const ACTION_USER_REVOKE_GUARANTOR_ROLE = 'GUARANTOR ROLE REVOKED';
    const ACTION_USER_REVOKE_COGUARANTOR_ROLE = 'COGUARANTOR ROLE REVOKED';

    const MODULE_USER_ADMIN = 'Admin';
    const MODULE_USER_OWNER = 'Owner';
    const MODULE_USER_SERVICE_PROVIDER = 'Service Provider';
    const MODULE_USER_BROKER = 'Broker';
    const MODULE_USER_CONTRACTOR = 'Contractor';
    const MODULE_USER_SUPPLIER = 'Supplier';
    const MODULE_BUILDING_ENTRANCE = 'Building Entrance';

    const MODULE_FEATURE = 'Feature';
    const MODULE_FEATURE_GROUP = 'Feature Group';
    const MODULE_PRODUCT_PACKAGE = 'Product Package';
    const MODULE_SERVICE_PLAN = 'Service Plan';

    const MODULE_APPLIANCE = 'Appliance';
    /**
     * GENERAL ACTIONS
     */
    const ACTION_CREATE = 'CREATED';
    const ACTION_UPDATE = 'UPDATED';
    const ACTION_DELETE = 'DELETED';
    const ACTION_TURN_ON = 'TURNED ON';
    const ACTION_TURN_OFF = 'TURNED OFF';
    const ACTION_ACTIVATE = 'ACTIVATED';
    const ACTION_DEACTIVATE = 'DEACTIVATED';

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'entity_id');
    }
}
