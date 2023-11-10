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
            'order_id' => 'required',
            'grand_total' => 'required',
            'discount' => 'required|numeric|gte:0|max:100',
            'shipping_fee' => 'required|numeric|gte:0|'
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
