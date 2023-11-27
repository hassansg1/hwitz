<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPermissions extends Model
{
    //
    protected $table = "model_has_permissions";

    public $timestamps = null;
    protected $guarded = [];

    public function modelable()
    {
        return $this->morphTo();
    }
}
