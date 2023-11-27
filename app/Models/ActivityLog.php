<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'causer_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
