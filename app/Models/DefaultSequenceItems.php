<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSequenceItems extends Model
{
    //
    protected $table = "default_sequences_items";

    protected $guarded = null;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemTemplates(){
        return $this->hasMany(DefaultSequenceTemplates::class,'item_id','id');
    }
}
