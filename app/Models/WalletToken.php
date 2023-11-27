<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletToken extends Model
{
    use HasFactory;
    protected $guarded = [];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function autoGenerateOTP($length = 4, $chars = '1234567890')
	{
		$chars_length = (strlen($chars) - 1);
		$string = $chars[rand(0, $chars_length)];
		for ($i = 1; $i < $length; $i = strlen($string))
		{
            $r = $chars[rand(0, $chars_length)];
            if ($r != $string[$i - 1]) 
                $string .= $r;
		}
		return $string;
    }
    
    /**
     * Insert New OTP Wallet Token
     */
    public function insertNewWalletToken($token_password, $user_id = null)
	{
        if(!isset($user_id))
            $user_id = Auth::id();

        $token_exist = WalletToken::whereUserId($user_id);

        //Already exist token
        if($token_exist->count() > 0)  
        {
            $token_exist->delete(); //delete token
        }

        $newToken['token_password'] = $token_password; 
        $newToken['user_id'] = $user_id; 
        $newToken['expire_date'] = strtotime("+5 minutes"); 

        return WalletToken::create($newToken);
	}
}
