<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UncollectedIncome extends Model
{
    use HasFactory;

    public $table = "uncollected_income";

    public function items()
    {
        return $this->hasMany(AchBatchItem::class, 'ach_batch_id');
    }
}
