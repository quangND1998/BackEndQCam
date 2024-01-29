<?php

namespace Modules\Order\app\Http\Requests;

use App\Enums\OrderTransportState;
use Illuminate\Foundation\Http\FormRequest;

class ChangeTransportStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'status' => 'required|string|in:'
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
