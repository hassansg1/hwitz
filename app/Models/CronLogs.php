<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronLogs extends Model
{
    protected $guarded = [];

    public const ENDEDTEXT = "Cron Job End";
    public const STARTTEXT = "Cron Job Start";
    public const SUCCESS = 0;
    const ERROR = 1;
    const WARNING = 2;
    const NORMAL = 3;
    const ENDED = 4;

    public function category()
    {
        return $this->belongsTo(CronCategories::class, 'cron_category_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
