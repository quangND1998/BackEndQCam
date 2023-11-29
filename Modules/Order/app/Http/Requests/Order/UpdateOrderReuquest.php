<?php

namespace Modules\Order\app\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderReuquest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payment_method' => 'required',
            'address' => 'required',
            'city' => 'required',
            'district' => 'required',
            'wards' => 'required',
            'phone_number' => 'required',
            'vat' => 'nullable|numeric|gt:-1',
            'discount_deal' => 'nullable|nullable|gt:-1',
            'type' => 'required',
            'shipping_fee' => 'nullable|nullable|gt:-1',
            'amount_paid' => 'nullable|nullable|gt:-1',
            'images' => 'nullable',
            'images.*' => 'mimes:jpeg,png,jpg|max:2048',
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
