<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureGroups extends Model
{
    //
    protected $table = 'features';

    protected $guarded = [];

    public $timestamps = false;

    protected $appends = ['opt_out_discount', 'opt_out_discount_value'];

    public function getOptOutDiscountAttribute()
    {
        if ($this->optout_discount_percentage > 0) return ' ( -$ ' . applyPercent($this->owner_amount, $this->optout_discount_percentage) . ')';
        else return '';
    }

    public function getOptOutDiscountValueAttribute()
    {
        if ($this->optout_discount_percentage > 0) return applyPercent($this->owner_amount, $this->optout_discount_percentage);
        else return '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Features::class, 'addons_features', 'feature_id', 'addon_id')->withPivot('price_included');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function packageGroup()
    {
        return $this->hasOne(BuildingPackageGroup::class, 'group_id', 'id');
    }

    public function receivers()
    {
        return $this->morphMany(ProfitReceivers::class, 'receiveable');
    }

    public function getElectionsAttribute($value)
    {
        if ($value)
            return explode(',', $value);
        else
            return [];
    }

    public function featureTaxes()
    {
        return $this->morphMany(TaxFeatureable::class, 'featureable');
    }
}
