<?php

namespace App\Models;

use App\Models\WalletBuildingRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Wallets extends Model
{

    protected $table = 'wallets';
    public $salt = "myurbanskyach123";
    protected $guarded = [];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    use SoftDeletes;

    protected static $cartType = [
        2 => 'amenities',
        3 => 'additional_rent',
        4 => 'laundry',
        '' => ''
    ];


    protected $appends = ['cart_type'];

    public function getCartTypeAttribute()
    {
        return self::$cartType[$this->trans_source_id];
    }

    /**
     * Decrypt the Bnk Acc No value in proper formate.
     *
     * @param date $value
     * @return date
     */
    public function getBnkAccNoAttribute($value)
    {
        if ($this->decrypt($value))
            return "xx" . substr($this->decrypt($value), -4);
    }

    /**
     * Decrypt the bnk routing number in proper formate.
     *
     * @param date $value
     * @return date
     */
    public function getBnkRoutingNoAttribute($value)
    {
        if ($this->decrypt($value))
            return "xx" . substr($this->decrypt($value), -4);
    }

    /**
     * encrypt the Bnk Acc No value before saving in the db.
     *
     * @param date $value
     * @return date
     */
    public function setBnkAccNoAttribute($value)
    {
        return $this->attributes['bnk_acc_no'] = $this->encrypt($value);
    }

    /**
     * encrypt the bnk routing number before saving in the db.
     *
     * @param date $value
     * @return date
     */
    public function setBnkRoutingNoAttribute($value)
    {
        return $this->attributes['bnk_routing_no'] = $this->encrypt($value);
    }

    /**
     * Save last 4 digit of a cradit card in DB.
     *
     * @param date $value
     * @return date
     */
    public function setCardNumberAttribute($value)
    {
        return $this->attributes['card_number'] = substr($value, -4);
    }

    /**
     *
     */
    public function encrypt($data)
    {
        $method = 'AES-256-CBC';
        $ivSize = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $key = hash('sha256', $this->salt);

        $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
        $encrypted = base64_encode($iv . $encrypted);

        return $encrypted;
    }

    public function decrypt($data)
    {
        if (is_numeric($data))
            return $data;
        error_log(date('Y-m-d H:i:s') . " $data\n", 3, storage_path('logs/wallets.log'));

        $method = 'AES-256-CBC';
        $key = hash('sha256', $this->salt);
        $data = base64_decode($data);

        error_log(date('Y-m-d H:i:s') . " $data\n", 3, storage_path('logs/wallets.log'));

        $ivSize = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

        error_log(date('Y-m-d H:i:s') . " $data\n", 3, storage_path('logs/wallets.log'));

        return $data;
    }

    /*
    * The wallet that belongs to many buildings
    */
    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'wallet_building_relations', 'wallet_id', 'building_id');
    }

    public function walletBuildings()
    {
        return $this->hasMany(WalletBuildingRelations::class, 'wallet_id');
    }

    public function walletRelation()
    {
        return $this->hasOne(WalletBuildingRelations::class, 'wallet_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
