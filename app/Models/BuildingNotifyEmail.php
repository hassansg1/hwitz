<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingNotifyEmail extends Model
{
    use HasFactory;

    protected $table = 'buildings_notifyemails';

    protected $guarded = [];

    public $timestamps = false;
}
