<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderLog extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'workorder_log';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    public function workorder()
    {
        return $this->belongsTo(WorkOrder::class, 'workorder_id');
    }
    public function priorityLogs(){
        return $this->hasMany(WorkOrderPriorityLog::class,'work_order_id','workorder_id');
    }

}
