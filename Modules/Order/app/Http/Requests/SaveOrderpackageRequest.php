<?php

namespace Modules\Order\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderpackageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'idPackage' => 'required|unique:order_packages,idPackage',
            'payment_method' => 'required',
            'address' => 'required',
            'city' => 'required',
            'district' => 'required',
            'wards' => 'required',
            'phone_number' => 'required',
            'vat' => 'nullable|numeric|between:0,100',
            'discount_deal' => 'nullable|nullable|between:0,100',
            'type' => 'required',
            'shipping_fee' => 'nullable|nullable|gt:-1',
            'time_reservations' => 'required|gt:0',
            'price_percent' => 'required|gt:-1',
            'product_selected' => 'required',
            'time_approve' =>'required',
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

    public function messages()
    {
        return [
            'idPackage.required' => 'Chưa điền số phiếu',
            'idPackage.unique:order_packages,idPackage' => 'Mã phiếu đã tồn tại trên hệ thống',
            'images.required' => 'Ảnh chứng từ liên quan phải có',
            "images.*.mimes" => 'Phải là định dạng ảnh',
        ];
    }
}
