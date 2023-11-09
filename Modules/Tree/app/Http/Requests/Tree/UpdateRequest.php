<?php

namespace Modules\Tree\app\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('trees', 'name')->ignore($this->tree),

            ],
            'address' => 'required',
            'price' => 'numeric|gt:0',
            'state' => 'required',
            'status' => 'required',
            'drescription' => 'required',
            'user_manual' => 'nullable',
            'terms_policy' => 'nullable'
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
