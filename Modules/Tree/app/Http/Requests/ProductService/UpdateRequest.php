<?php

namespace Modules\Tree\app\Http\Requests\ProductService;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'number_tree' => 'required|numeric|gt:0',
            'acreage' => 'required|numeric|gt:0',
            'free_visit' => 'required|numeric|gt:0',
            'amount_products_received' =>  'required|numeric|gt:0',
            'price' =>  'required|numeric|gt:0',
            'number_deliveries' => 'required|numeric|gt:0',
            'life_time' =>  'required|numeric|gt:0',
            'description' => 'required',
            'unit' => 'required',
            // 'images' => 'required',
            // 'images.*' => 'mimes:jpeg,png,jpg|max:2048',
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
