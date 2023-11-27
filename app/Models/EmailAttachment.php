<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'email_attachments';
    public $timestamps = false;
}
