<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table='exceptions';


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
