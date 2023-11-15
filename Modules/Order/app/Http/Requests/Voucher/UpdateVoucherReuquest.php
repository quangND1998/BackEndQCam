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
            'min_spend' => 'required',
            'is_fixed' => 'required',
            // 'discount_caption' => 'required|numeric|gt:-1',
            'discount_percentage' => 'nullable|numeric|gt:-1',
            'discount_value' => 'required|numeric|gt:-1',
            'discount_max_value' => 'required_if:discount_percentage,>,0',
            'starts_at' => 'required|date',
            'expires_at' =>  'required|date|after:starts_at',
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
