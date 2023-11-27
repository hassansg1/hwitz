<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationSequence extends Model
{
    use HasFactory;

    protected $table = 'verification_sequence';
    protected $guarded = [];
    public $timestamps = false;
}
