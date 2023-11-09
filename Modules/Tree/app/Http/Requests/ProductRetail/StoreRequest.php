<?php

namespace Modules\Tree\app\Http\Requests\ProductRetail;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:product_retails,name',
            'price' => 'required|numeric|gt:0',
            'description' => 'required',
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
