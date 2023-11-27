<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $appends = ['group'];

    protected $fillable = [
        'created_by', 'updated_by', 'name', 'schedule_type', 'start_date', 'end_date', 'start_time', 'end_time', 'start_time2', 'end_time2', 'start_time3', 'end_time3', 'schedule_name_id', 'owner_id', 'device_id', 'dow', 'schedule_special_id','schedule_periods','building_id','type','brivo_id'
    ];

    protected $casts = [
        'schedule_periods' => 'array'
    ];
    public $timestamps = true;

    public function getGroupAttribute(){
        return 'Select existing';
    }

}
