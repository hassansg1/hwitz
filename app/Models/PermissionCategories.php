<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionCategories extends Model
{
    //
    protected $table = "permission_categories";

    protected $guarded = [];

    public function permissions(){
        return $this->hasMany(AccessPermission::class,'category_id');
    }
}
