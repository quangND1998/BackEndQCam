<?php

namespace Modules\Order\app\Http\Requests\Voucher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVoucherReuquest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                Rule::unique('vouchers', 'code')->ignore($this->voucher),

            ],
            'name' => 'required',
            'description' => 'nullable',
            'uses' => 'nullable',
            'max_uses_user' => 'nullable',
            'max_uses' => 'nullable',
            'type' => 'required',
            'unit' => 'required',
            'is_fixed' => 'required',
            'discount_amount' => 'required|numeric|gt:0',
            'starts_at' => 'required|date',
            'expires_at' =>  'required|date|after:starts_at',
            'type_product' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        // return auth()->user()->can('users.create');
    }
}
