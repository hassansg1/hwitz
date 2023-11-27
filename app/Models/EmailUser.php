<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailUser extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'emails_users';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function creator(){
        return $this->belongsTo(User::class,'creator_id');
    }

    public function attachments(){
        return $this->hasMany(EmailAttachment::class,'message_id');
    }
}
