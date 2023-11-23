<?php

namespace Modules\Order\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderGiftPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_service_owner_id' => 'required',
            'address' => 'required',
            'city' => 'required',
            'district' => 'required',
            'wards' => 'required',
            'phone_number' => 'required',
            'type' => 'required',
            'images' => 'required',
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
