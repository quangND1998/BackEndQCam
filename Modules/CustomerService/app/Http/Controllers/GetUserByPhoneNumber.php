<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUserByPhoneNumber extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::where('isActive', 1)
            ->where(function ($query) use ($request) {
                $query->where('phone_number', $request->phone_number)
                    ->orWhere('phone_number2',  $request->phone_number);
            })
            ->first();

        return response()->json([
            'message' => 'Ok',
            'user' => $user,
        ]);
    }
}
