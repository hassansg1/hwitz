<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderComment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'workordercomments';

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

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
