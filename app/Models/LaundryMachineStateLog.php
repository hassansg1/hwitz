<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class LaundryMachineStateLog extends Model
{
    protected $guarded = [];

    protected $table = "laundrymachines_state_log";

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public $timestamps = false;

    public function laundryMachine()
    {
        return $this->belongsTo(LaundryMachine::class, 'machine_id');
    }

    public function laundryMachineState()
    {
        return $this->belongsTo(LaundryMachineState::class, 'state', 'state');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
