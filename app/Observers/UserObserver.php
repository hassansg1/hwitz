<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $auth_user = Auth::user();
        $admin_id = 1;
        $tenant_id = 13;
        $landlord_id = 15;
        $is_admin = $auth_user->is_admin;
        $user_id = $user->id;
        $usertype_id = $user->usertype_id;
        $loggedin_user_type = $auth_user->usertype_id;
        $is_verified = !empty($user->is_profile_verified) ? $user->is_profile_verified : '';

        $user->update(['created_by' => Auth::id()]);

        $this->sendWelcomeEmail($user);


    }

    private function sendWelcomeEmail($user){
        $id = uniqid();
        $key = rand(100000000,1000000000);

        $user->update([
            'email_token_expires' => date('Y-m-d H:i:s'),
            'password_token' => $key,
            'verification_email' => $id,
        ]);

        if($user['usertype_id'] ==  13){
            $url = rtrim(config('app.RP_URL'), '/') . '/users/reset_password/' . $key;
            $this->sendVerificationLinksToResident($user);
        }else{
            $url = rtrim(config('app.portal_url'),'/') . '/users/reset_password/' . $key;
        }

        $name = $user['firstname'] . ' ' . $user['lastname'];

        $msg = "
                Hello $name, \n

                Welcome to Urban Sky. We're delighted you're part of our community known for saving up to 40% on Amenities services including cable TV, Gigabit Internet and Unlimited telephone service. Once joining our community, 30% of residents decide to stay longer because a smart building greatly increases peace of mind and convenience while providing valuable service at deep discounts. \n 

                Please create a new password by following this link. $url \n

                Feel free to call our tech support number anytime as we close out work orders in less than 1 business day. 877-830-3803. \n

                Regards,\n
                Urban Sky
                ";

        sendEmail($msg,$user['email'],null,'Welcome to Urban Sky','',false);
    }

    private function sendVerificationLinksToResident($user){
        $name = $user['firstname'];
        $key = md5($user['email']);
        $url = rtrim(config('app.RP_URL'), '/') . '/users/verify_email/' . $key;

        $msg = '
                Hello ' . $name . ' \n,

                Click on the below link to verify your email. \n

                ' . $url . ' \n


                Regards, \n
                Urban Sky
                ';
                
        sendEmail($msg,$user['email'],null,'Email Verification','',false);

        $url = rtrim(config('app.RP_URL'), '/') . '/users/verify_sms/' . $key;

        $msg = '
                Hello ' . $name . ',

                Click on the below link to verify your mobile number. 

                ' . $url . '


                Regards,
                Urban Sky
                ';
        sendSMS($msg,$user['mobile']);
    }
}
