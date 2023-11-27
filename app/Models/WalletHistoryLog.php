<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistoryLog extends Model
{
    protected $guarded = [];
    //public $timestamps = false;
    const CREATED_AT = 'created';
    const UPDATED_AT = null;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallets::class, 'wallet_id');
    }
}
