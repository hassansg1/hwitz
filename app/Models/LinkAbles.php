<?php

namespace App\Models;

use App\Models\LinkAblesItems;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class LinkAbles extends Model
{
    //
    protected $table = "linkables";

    protected $guarded = [];

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    public static $fields = [
        'adding_amenities' => 'transaction_log',
        'amenities' => 'amenity_balance',
        'rent' => 'rent_balance',
        'laundry' => 'laundry_balance',
    ];

    public static $labels = [
        'adding_amenities' => 'Amenities Service Subscription',
        'amenities' => 'Amenity',
        'rent' => 'Rent',
        'laundry' => 'Laundry',
    ];
    public static $description = [
        'adding_amenities' => 'Amenities Service Subscription',
        'amenities' => 'Amenities Payment',
        'rent' => 'Rent Payment',
        'laundry' => 'Laundry Payment',
    ];
    public static $wallets = [
        'adding_amenities' => 'amenityWallet',
        'amenities' => 'amenityWallet',
        'rent' => 'rentWallet',
        'laundry' => 'laundryWallet',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function linkable()
    {
        return $this->morphTo();
    }


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public static function getToken($user_id, $unit_id, $type)
    {

        $token = Str::random(8);

        $row = DB::table('linkables')
            ->select('*')
            ->where('status', 'pending')
            ->where('linkable_id', $user_id)
            ->where('unit_id', $unit_id)
            ->where('type', $type)
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if (!$row || 0) {

            DB::table('linkables')->insert(
                [
                    'linkable_id' => $user_id,
                    'linkable_type' => "App\User",
                    'unit_id' => $unit_id,
                    'token' => $token,
                    'type' => $type,
                    'status' => 'pending',
                    'expires_at' => Carbon::now()->addDay(7)
                ]
            );
        } else {

            $token = $row->token;
        }

        return $token;
    }

    public function items()
    {
        return $this->hasMany(LinkAblesItems::class, 'linkables_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallets::class, 'wallet_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'linkable_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(PaymentDetails::class, 'linkables_id');
    }


    /**
     * @param $transactionLogs
     * @param $userId
     * @param $unitId
     * @param $token
     * @param $type
     * @return mixed
     */
    public static function createLinkAble($transactionLogs, $userId, $unitId, $token, $type, $cartType = "")
    {
        $term_data['linkable_id'] = $userId;
        $term_data['linkable_type'] = 'App\User';
        $term_data['unit_id'] = $unitId;
        $term_data['token'] = $token;
        $term_data['type'] = $type;
        $term_data['cart_type'] = $cartType;
        $term_data['status'] = 'pending';
        $term_data['expires_at'] = date('Y-m-d', strtotime(date('Y-m-d H:i:s') . ' + 30 days'));
        $term_data['created_at'] = date('Y-m-d H:i:s');
        $term_data['updated_at'] = date('Y-m-d H:i:s');
        $linkableData = self::create($term_data);

        $term_data = [];
        foreach ($transactionLogs as $transactionLog) {
            $term_data['itemable_id'] = $transactionLog;
            $term_data['itemable_type'] = 'App\TransactionLog';
            $term_data['linkables_id'] = $linkableData->id;
            $term_data['status'] = 0;
            LinkAblesItems::create($term_data);
        }

        return $linkableData;
    }
}
