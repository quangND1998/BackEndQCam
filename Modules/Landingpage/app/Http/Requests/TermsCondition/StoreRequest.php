<?php

namespace Modules\Landingpage\app\Http\Requests\TermsCondition;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'address' => 'required|string',
            'tax_code' => 'required|string',
            'hotline' => 'required|string',
            'zalo_phone' => 'required|string',
            'facebook' => 'required|string',
            'website' => 'required|string',
            'map' => 'required|string'
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
