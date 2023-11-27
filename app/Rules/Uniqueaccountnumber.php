<?php

namespace App\Rules;

use App\Models\Wallets;
use Illuminate\Contracts\Validation\Rule;

class Uniqueaccountnumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $userId = request()->user_id;
        if(isset(request()->id))
        {
            $wallets = Wallets::select('bnk_acc_no','bnk_routing_no')
                        ->where('user_id', $userId)
                        ->where('id','!=',request()->id)
                        ->get();
        }    
        else
        {
            $wallets = Wallets::select('bnk_acc_no','bnk_routing_no')
                        ->where('user_id', $userId)
                        ->get();
        }    
        
        foreach ($wallets as $wallet) {
            if (((substr($wallet->bnk_acc_no,-4)) == substr($value, -4)) && ((substr($wallet->bnk_routing_no,-4)) == substr(request()->bnk_routing_no, -4))) {
                
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Bank Account Number and Bank Routing Number must be unique.';
    }
}
