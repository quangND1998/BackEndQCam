<?php

namespace Modules\Tree\app\Http\Requests\ProductService;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'unit' => 'required'
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
            "number_tree.gt" =>  'You have invalid characters in the value getter than 0',
            "acreage.gt" =>  'You have invalid characters in the value getter than 0',
            "free_visit.gt" =>  'You have invalid characters in the value getter than 0',
            "amount_products_received.gt" =>  'You have invalid characters in the value getter than 0',
            "price.gt" =>  'You have invalid characters in the value getter than 0',
            "number_deliveries.gt" =>  'You have invalid characters in the value getter than 0',
            "life_time.gt" =>  'You have invalid characters in the value getter than 0',

            'number_tree.required' => 'The number_tree field is required.',
        ];
    }
}
