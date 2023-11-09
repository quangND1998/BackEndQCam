<?php

namespace Modules\Tree\app\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:trees,name',
            // 'address' => 'required',
            // 'price' => 'numeric|gt:0',
            // 'state' => 'required',
            // 'status' => 'required',
            // 'drescription' => 'required',
            // 'user_manual' => 'nullable',
            // 'terms_policy' => 'nullable',
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
