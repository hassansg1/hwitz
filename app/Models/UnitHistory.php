<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UnitHistory extends Model
{

    protected $table = 'unit_history';
    protected $guarded = [];

    public $timestamps = null;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function addUserUnitistory($unit_id, $user_id){
        $data['unit_id'] = $unit_id;
		$data['user_id'] = $user_id;
		$data['move_time'] = date("Y-m-d H:i:s");
		$data['status'] = 1;
        return self::create($data);
    }

    public static function update_user_unit_history($unit_id, $user_id){
		
		$data['move_out_time'] = date('Y-m-d H:i:s');
		$data['status'] = 0;

        return self::updateOrCreate([
            'unit_id' => $unit_id,
            'user_id' => $user_id
        ],$data);
		// return ($this->updateAll($data, array('unit_id' => $unit_id, 'user_id' => $user_id, 'status' => 1))) ? true : false;
	}
}
