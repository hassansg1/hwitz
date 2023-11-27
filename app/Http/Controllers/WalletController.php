<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletStoreRequest;
use App\Http\Requests\WalletUpdateRequest;
use App\Libs\TransNational;
use App\Models\Tasks;
use App\Models\User;
use App\Models\WalletHistoryLog;
use App\Models\Wallets;
use App\Services\Parsers\WalletParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WalletController extends Controller
{
    protected $prevUrl;
    protected $tnbci_username;
    protected $tnbci_password;
    protected $tnbci_prefix;
    protected $objTranscNational;

    public function __construct()
    {
        // Set security token same as done in CakePHP
        config(['app.Security.salt' => config('app.SALT')]);
        $this->tnbci_username = config('app.TNBCI_USER');
        $this->tnbci_password = config('app.TNBCI_PASS');
        $this->tnbci_prefix = config('app.TNBCI_PREFIX');
        $this->objTranscNational = new TransNational();

        $host = parse_url(request()->headers->get('referer'), PHP_URL_HOST);
        error_log(date('Y-m-d H:i:s ') . "Host: $host\n", 3, storage_path('logs/admin.log'));
        $userAgent = request()->headers->get('user-agent');
        Session::put('prevUrl', url()->previous());
        $this->prevUrl = url()->previous();
    }

    public function addWalletHistoryLogs(Request $request, $arr)
    {
        $arr['ip_address'] = $request->ip();
        $arr['user_agent'] = $request->header('User-Agent') ?? 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36';
        WalletHistoryLog::create($arr);
    }

    public function getUserWallets(Request $request)
    {
        $data = app(WalletParser::class)->parse($request);

        return response()->json($data);
    }

    public function store(WalletStoreRequest $request, $userId, $portfolio, $origin)
    {
        $response_msg = 'Wallet added successfully';
        $walletObj = new Wallets();
        $prevUrl = $request->prevUrl;
        $walletType = ['p' => 0, 'r' => 1];
        $this->prevUrl = $request->prevUrl;
        //$cartType = ['4'=>'laundry','2'=>'amenities', '3'=>'rent'];
        if ($request->year) {
            $year = range(date("Y"), date("Y") + 10);
            array_unshift($year, '--Select Year--'); // prepend first element in the year array
            $cardExpiry = $request->month . '-' . substr($year[$request->year], -2);
            $data = array_merge($request->except(['_token', 'prevUrl', 'save_action', 'month', 'year', 'card_cvv', 'token_password']),
                ["card_expiry" => $cardExpiry, 'card_cvv' => '', 'wallet_type' => $walletType[$portfolio]]);
        } else {
            $data = array_merge($request->except(['_token', 'save_action', 'token_password']),
                ['wallet_type' => $walletType[$portfolio]]);
        }

        $wallet_user = User::find($request->user_id);

        // if (isUserResident($wallet_user)) {
        $data['default_rent'] = $request->default_rent ? 1 : 0;
        $data['default_amenities'] = $request->default_amenities ? 1 : 0;
        $data['default_laundry'] = $request->default_laundry ? 1 : 0;
        $data['autopay_amenities'] = $request->autopay_amenities ? 1 : 0;
        $data['autopay_rent'] = $request->autopay_rent ? 1 : 0;
        // }


        $wallet = Wallets::create($data);
        $current = Carbon::now();

        if ($portfolio == 'p' && $request->year) {
            $firstname = $wallet_user->firstname;
            $lastname = $wallet_user->lastname;
            $company = $fax = $country = $website = '';
            $address1 = $wallet['address1'];
            $address2 = $wallet['address2'];
            $city = $wallet['city'];
            $state = $wallet_user->state;
            $zip = $wallet['zipcode'];
            $phone = $wallet_user->mobile;
            $email = $wallet_user->email;

            $this->objTranscNational->setLogin($this->tnbci_username, $this->tnbci_password, $this->tnbci_prefix);
            $this->objTranscNational->setBilling($firstname, $lastname, $company, $address1, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $website);
            $vault_result = $this->objTranscNational->doVaultAdd($wallet->id, $request->card_number_full, $cardExpiry, $request->card_cvv);
            // dump($vault_result);
            switch ($vault_result['response']) {
                case 1:
                    $response_msg = 'Wallet is added Successfully.';
                    break;
                case 2:
                    $response_msg = 'Wallet adding failed. Reason: Declined by TransNational.';
                    dde($vault_result, 'vault.log');
                    break;
                default:
                    $response_msg = 'Wallet adding failed. Error from TransNational: ' . $vault_result['responsetext'];
                    dde($vault_result, 'vault.log');
                    break;
            }
            if ($vault_result['response'] != '1') {

                // If not sucessful delete wallet
                $wallet->delete();

                if (isUserResident(User::find($request->user_id))) {
                    return response()->json(['status' => 0, 'message' => $response_msg]);
                }
                return response()->json(['status' => 0, 'message' => $response_msg]);
            }
        }
        walletAccessNotification($current, $wallet);

        $log = ['wallet_id' => $wallet->id,
            'change_flag' => "Add",
            'wallet_logs' => 'Added',
            'created_by' => $wallet->user_id
        ];
        $this->addWalletHistoryLogs($request, $log);
        Tasks::where(['name' => 'Registering your E-Wallet', 'user_id' => $request->user_id])->update(['task_state' => 'C']);

        Tasks::where(['name' => 'Registering your E-Wallet', 'user_id' => $request->user_id])->update(['task_state' => 'C']);

        return response()->json(['status' => 1, 'message' => $response_msg]);
        // return view('wallet.message')->with(['prevUrl' => $this->prevUrl, 'message' => $response_msg]);


    }


    public function sendOTP($user_id)
    {
        $sendOTP = sendOTPToUser($user_id);

        if ($sendOTP) {
            echo "Please check your mobile for OTP, expiration time is 5 minutes!";
            exit;
        } else {
            echo 'Sorry, we could not send you OTP, please check your mobile number and try again.';
        }
    }

    public function resendOTP($user_id)
    {
        $sendOTP = sendOTPToUser($user_id);

        if ($sendOTP) {
            echo "Please check your mobile for a newly generated OTP, expiration time is 5 minutes!";
            exit;
        } else {
            echo 'Sorry, we could not send you OTP, please check your mobile number and try again.';
        }
    }

    public function destroy($id = null)
    {
        $wallet = Wallets::find($id);
        if ($wallet) {
            if ($wallet->payment_type == 1) {
                $this->objTranscNational->setLogin($this->tnbci_username, $this->tnbci_password, $this->tnbci_prefix);
                $api_response = $this->objTranscNational->doVaultDelete($id);
                dde($api_response, 'tnbci.log');
                if ($api_response['response'] == '1') {
                    $wallet->delete();
                    return response()->json(['status' => 'success', 'message' => 'Account deleted successfully!']);
                } else {
                    return response()->json(['status' => 'warning', 'message' => 'Account Not Deleted ' . $api_response['responsetext']]);
                }
            }
        } else {
            $wallet->delete();
        }
        return response()->json(['status' => 1, 'message' => 'Account deleted successfully!']);
    }

    public function update(WalletUpdateRequest $request, $id, $portfolio, $origin)
    {
        $response_msg = 'Wallet Updated Successfully.';
        $this->prevUrl = $request->prevUrl;
        $wallet = Wallets::find($id);
        // following array is used for wallet logging
        $oldWallet['bnk_routing_no'] = $wallet->getOriginal('bnk_routing_no');
        $oldWallet['bnk_acc_no'] = $wallet->getOriginal('bnk_acc_no');
        //dd($oldWallet);
        $walletObj = new Wallets();
        $walletType = ['p' => 0, 'r' => 1];
        $cartType = ['4' => 'laundry', '2' => 'amenities', '3' => 'rent'];

        if ($request->year) {
            $year = range(date("Y"), date("Y") + 20);
            array_unshift($year, '--Select Year--'); // prepend first element in the year array
            $cardExpiry = $request->month . '-' . substr($year[$request->year], -2);
            $data = array_merge($request->except(['_token', 'prevUrl', 'save_action', 'month', 'year', 'card_cvv', 'token_password']),
                ["card_expiry" => $cardExpiry, 'card_cvv' => '', 'wallet_type' => $walletType[$portfolio]]);

            if ($data['card_number'] != $wallet['card_number'] || $request->card_cvv != $wallet['card_cvv'] || $data['card_expiry'] != $cardExpiry) {
                $wallet_user = User::find($request->user_id);
                $firstname = $wallet_user->firstname;
                $lastname = $wallet_user->lastname;
                $company = $fax = $country = $website = '';
                $address1 = $wallet['address1'];
                $address2 = $wallet['address2'];
                $city = $wallet['city'];
                $state = $wallet_user->state;
                $zip = $wallet['zipcode'];
                $phone = $wallet_user->mobile;
                $email = $wallet_user->email;

                $this->objTranscNational->setLogin($this->tnbci_username, $this->tnbci_password, $this->tnbci_prefix);
                $this->objTranscNational->setBilling($firstname, $lastname, $company, $address1, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $website);
                $vault_result = $this->objTranscNational->doVaultUpdate($id, $request->card_number_full, $cardExpiry, $request->card_cvv);

                switch ($vault_result['response']) {
                    case 1:
                        $response_msg = 'Wallet is added Successfully.';
                        break;
                    case 2:
                        $response_msg = 'Wallet is added in Db but Declined by TransNational.';
                        dde($vault_result, 'vault.log');
                        break;
                    default:
                        $response_msg = 'Wallet is added in Db but not on TransNational, Error from TransNational: ' . $vault_result['responsetext'];
                        dde($vault_result, 'vault.log');
                        break;
                }
                if ($vault_result['response'] != '1') {
                    $wallet->delete();
                    return response()->json(['status' => 'warning', 'message' => $response_msg]);
                }

            }
        } else {

            $data = array_merge($request->except(['_token', 'save_action', 'token_password']),
                ['wallet_type' => $walletType[$portfolio]]);

            if ($wallet->bnk_acc_no == $request->bnk_acc_no)
                unset($data['bnk_acc_no']); // if bnk_acc_no is not updated, then remove from the posted data

            if ($wallet->bnk_routing_no == $request->bnk_routing_no)
                unset($data['bnk_routing_no']); // if bnk_routing_no is not updated, then remove from the posted data
        }


        unset($data['month']);
        unset($data['year']);

        if (isUserResident(User::find($request->user_id))) {
            $data['default_rent'] = $request->default_rent ? 1 : 0;
            $data['default_amenities'] = $request->default_amenities ? 1 : 0;
            $data['default_laundry'] = $request->default_laundry ? 1 : 0;
            $data['autopay_amenities'] = $request->autopay_amenities ? 1 : 0;
            $data['autopay_rent'] = $request->autopay_rent ? 1 : 0;
        }

        $wallet->update($data);

        $getChangedFields = $wallet->getChanges();
        foreach ($getChangedFields as $fieldName => $fieldVal) {
            if ($fieldName == 'bnk_routing_no' || $fieldName == 'bnk_acc_no') {
                $walletHistoryLog =
                    [
                        'wallet_id' => $id,
                        'change_flag' => 'BankNumber',
                        'wallet_logs' => 'Bank Number Changed from ' . $walletObj->decrypt($oldWallet["$fieldName"]),
                        'created_by' => $wallet->user_id
                    ];
                $this->addWalletHistoryLogs($request, $walletHistoryLog);
                $editFlage = true;
            }
        }
        if (!isset($editFlage)) {
            $walletHistoryLog =
                [
                    'wallet_id' => $id,
                    'change_flag' => 'Edit',
                    'wallet_logs' => 'Edited',
                    'created_by' => $wallet->user_id
                ];
            $this->addWalletHistoryLogs($request, $walletHistoryLog);
        }
        $current = Carbon::now();
        walletAccessNotification($current, $wallet);
        return response()->json(['status' => 'success', 'message' => $response_msg]);
    }
}
