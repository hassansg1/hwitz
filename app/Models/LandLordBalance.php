<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandLordBalance extends Model
{
    use HasFactory;

    protected $table = 'landlord_balances';

    protected $guarded = [];

    public $timestamps = null;
}
