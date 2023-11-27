<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionLog extends Model
{
    //
    protected $table = 'transaction_logs';

    public $timestamps = false;

    protected $guarded = [];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public static $carts = [
        2 => 'amenity_balance',
        3 => 'rent_balance',
        4 => 'laundry_balance',
    ];

    public static $transToCart = [
        2 => 'amenities',
        3 => 'additional_rent',
        4 => 'laundry',
    ];

    public static $transToCartOther = [
        2 => 'amenities',
        3 => 'rent',
        4 => 'laundry',
    ];

    public static $tranceSourceId = [
        'rent' => 3,
        'additional_rent' => 3,
        'amenities' => 2
    ];

    public static $balanceField = [
        'rent' => 'rent_balance',
        'additional_rent' => 'rent_balance',
        'amenities' => 'amenity_balance'
    ];

    public static $cartLabel = [
        2 => 'Amenities',
        3 => 'Additional Rent',
        4 => 'Laundry',
        null => ''
    ];

    public static $cartLabelByCartType = [
        'rent' => 'Rent',
        'additional_rent' => 'Rent',
        'amenities' => 'Amenities',
        null => ''
    ];

    protected $appends = ['cart_label', 'description','tr_month'];

    public function getDescriptionAttribute()
    {
        return json_decode($this->notes)->description ?? '';
    }

    public function getTrMonthAttribute()
    {
        return Carbon::parse($this->timestamp)->format("M Y");
    }

    public function getCartLabelAttribute()
    {
        return self::$cartLabel[$this->trans_source_id] ?? '';
    }

    /** */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function laundry()
    {
        return $this->belongsTo(LaundryRoom::class, 'laundry_id');
    }


    public function laundryMachine()
    {
        return $this->belongsTo(LaundryMachine::class, 'laundrymachine_id');
    }

    public function token()
    {
        return $this->belongsTo(Token::class, 'token_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function initPerson()
    {
        return $this->belongsTo(User::class, 'init_person_id');
    }

    public function reconciledPerson()
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }


    public function entryLog()
    {
        return $this->hasOne(EntryLog::class, 'device_log_id', 'id')->where('device_id', 6);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function paymentLink()
    {
        return $this->belongsTo(LinkAbles::class, 'payment_link_id');
    }

    public function feature()
    {
        return $this->belongsTo(FeatureGroups::class, 'feature_id');
    }

    /** */
    public function transSource()
    {
        return $this->belongsTo(TransSource::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }

    /**
     * Lined links
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function linkableitems()
    {
        return $this->morphMany(LinkAblesItems::class, 'itemable');
    }
}
