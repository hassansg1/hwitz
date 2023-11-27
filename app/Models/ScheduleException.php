<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleException extends Model
{
    use HasFactory;

    protected $table='schedule_exceptions';
    protected $guarded=[];
    public function exception()
    {
        return $this->belongsTo(Exception::class, 'exception_id');
    }
    public function schedule()
    {
        return $this->belongsTo(ScheduleName::class, 'schedule_id');
    } 
}
