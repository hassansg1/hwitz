<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderPriorityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function modifiedBy(){
        return $this->belongsTo(User::class,'modified_by');
    }
}
