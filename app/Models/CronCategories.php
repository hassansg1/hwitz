<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronCategories extends Model
{
    //
    protected $table = 'cron_categories';

    protected $guarded = [];

    public static function findOrCreateByKey($key, $value, $description)
    {
        // Strip extra after space
        if (strpos($key,' '))
            $key = trim(substr($key, 0, strpos($key,' ')));

        $item = CronCategories::where('key', $key)->first();

        if ($item) return $item;

        $item = CronCategories::create([
            'key' => $key,
            'value' => $value,
            'description' => $description,
        ]);

        return $item;
    }
}
