<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['accepted_eula_version_link', 'name', 'initials', 'verified'];


    public function getNameAttribute()
    {
        return $this->attributes['name'] = $this->firstname . ' ' . $this->lastname;
    }


    function isSuperAdmin()
    {
        $superAdmins = Configurations::where('name', 'superAdmin')->first();
        if ($superAdmins) {
            $superAdmins = explode(',', $superAdmins->value);
            if (count($superAdmins) > 0) {
                if (in_array($this->id, $superAdmins))
                    return true;
            }
        }
        return false;
    }

    public function accessPermissions()
    {
        return $this->morphMany(ModelPermissions::class, 'modelable');
    }

    public function getVerifiedAttribute()
    {
        return $this->email_verified == 1 && $this->mobile_verification == "Yes";
    }

    public function getInitialsAttribute()
    {
        return substr($this->firstname, 0, 1) . ' ' . substr($this->lastname, 0, 1);
    }

    public function tokens()
    {
        return $this->hasMany('App\Models\Token', 'user_id');
    }

    public function token()
    {
        return $this->hasOne('App\Models\Token', 'user_id');
    }

    public function usertype()
    {
        return $this->belongsTo(UserTypes::class, 'usertype_id');
    }

    public function getAcceptedEulaVersionLinkAttribute()
    {
        return $this->accpeted_eula_version ? config('app.portal_url') . '/signdoc/view_eula/' . $this->accpeted_eula_version : $this->accpeted_eula_version;
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'buildings_users', 'user_id');
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'units_users', 'user_id', 'unit_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'parent_unit_id');
    }

    public function buildingIds()
    {
        return $this->buildings->pluck('id')->toArray();
    }

    public function macs()
    {
        return $this->hasMany(UnitMac::class, 'user_id');
    }
}
