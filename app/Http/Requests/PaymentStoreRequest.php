<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice' => 'required|integer|unique:payments',
            'recipient_name' => 'required',
            'value' => 'required|numeric|min:0.01|max:100000',
            'recipient_bank_code' => 'required|digits_between:1,3',
            'recipient_branch_number' => 'required|digits_between:1,4',
            'recipient_account_number' => 'required|digits_between:1,15',
        ];
    }
}
