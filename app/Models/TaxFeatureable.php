<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxFeatureable extends Model
{
    protected $guarded = [];

    public function featureable()
    {
        return $this->morphTo();
    }
}
