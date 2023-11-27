<?php

namespace App\Models;

use App\Scopes\BuildingScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Building extends Model
{
    use HasFactory;

    protected $guarded = [];


    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BuildingScope());
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function doors()
    {
        return $this->hasMany(Door::class,'building_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'building_id');
    }
    public function laundry()
    {
        return $this->hasMany(LaundryRoom::class,'building_id');
    }

    public function appliance()
    {
        return $this->hasMany(LaundryMachine::class, 'building_id');
    }

    public function lockers()
    {
        return $this->hasMany(Locker::class, 'building_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class, 'building_id');
    }
    public function wallets()
    {
        return $this->belongsToMany('App\Models\Wallets', 'wallet_building_relations', 'building_id', 'wallet_id');
    }
}
