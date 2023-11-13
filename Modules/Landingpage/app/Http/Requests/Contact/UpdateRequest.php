<?php

namespace Modules\Landingpage\app\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'tax_code' => 'nullable|string',
            'hotline' => 'required|string',
            'zalo_phone' => 'nullable|string',
            'facebook' => 'nullable|string',
            'website' => 'nullable|string',
            'map' => 'nullable|string'
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
