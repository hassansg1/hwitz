<?php

namespace App\Http\Requests;

use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\Uniqueaccountnumber;
use Illuminate\Foundation\Http\FormRequest;

class WalletUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('card_number')) {
            $this->merge(['card_number' => substr($this->card_number, -4)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * In update case there is some modification that's why request is rewritten for the update case
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $walletObj = new Wallets();
        return [
            'cart_type' => 'sometimes|required',
            'bnk_routing_no' => 'sometimes|required',
            'bnk_acc_no' => [new Uniqueaccountnumber()],
            'check_holder_type' => 'sometimes|required',
            'check_type' => 'sometimes|required',
            'check_holder_type' => 'sometimes|required',
            'card_number' => ['sometimes', 'required', Rule::unique('wallets')->where(function ($query) use ($request, $walletObj) {
                return $query
                    ->where('payment_type', 1)
                    ->where('user_id', $request->user_id)
                    ->whereNull('deleted_at')
                    ->where('card_type', $request->card_type);
            })->ignore($request->id)],
            'card_type' => 'sometimes|required',
            'month' => 'sometimes|required|numeric|min:0|not_in:0',
            'year' => 'sometimes|required|numeric|min:0|not_in:0',
            'card_cvv' => 'sometimes|required',

//            'token_password'=>  Rule::exists('wallet_tokens')->where(function ($query) use($request){
//                $query->where('user_id', $request->user_id);
//                $query->where('expire_date','>', strtotime('now'));
//            }),
            'name_on_card' => 'required',
            'city' => 'sometimes|required',
            'address1' => 'required',
            'email' => 'email',
            'zipcode' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'trans_source_id.required' => 'The Cart Type field is required.',
            'bnk_routing_no.required' => 'The Bank Routing Number field is required.',
            'bnk_acc_no.required' => 'The Bank Account Number field is required.',
            'bnk_routing_no.numeric' => 'The Bank Routing Number must be a number.',
            'bnk_acc_no.numeric' => 'The Bank Account Number must be a number.',
            'name_on_card.required' => 'The Name on Account field is required.',
            'bnk_routing_no.min' => 'The Bank Routing Number must be at least 9.',
            'bnk_acc_no.min' => 'The Bank Account Number must be at least 7.',
            'month.not_in' => 'The :attribute is required.',
            'year.not_in' => 'The :attribute is required.',
        ];
    }
}
