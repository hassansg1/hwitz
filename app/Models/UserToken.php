<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserToken extends Model
{
    use HasFactory;

    protected $table = 'user_tokens';
    protected $guarded = [];
    public $timestamps = false;
    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];

    public static function ozekiOTP($length = 8, $chars = '1234567890')
    {
        $chars_length = strlen($chars) - 1;
        $string = $chars[rand(0, $chars_length)];
        
        for ($i = 1; $i < $length; $i = strlen($string)) {
            $r = $chars[rand(0, $chars_length)];
            
            if ($r !== $string[$i - 1]) {
                $string .= $r;
            }
        }
        
        return $string;
    }

    public static function email_token($length = 30, $chars ='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
	{
		$chars_length = (strlen($chars) - 1);
		$string = $chars{rand(0, $chars_length)};
		for ($i = 1; $i < $length; $i = strlen($string))
		{
		$r = $chars{rand(0, $chars_length)};
		if ($r != $string{$i - 1}) $string .= $r;
		}
		return $string;
	}

}
