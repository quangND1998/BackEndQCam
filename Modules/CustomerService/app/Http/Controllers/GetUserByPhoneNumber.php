<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Order\app\Models\OrderPackage;

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
        if ($user) {
            $activePackage = OrderPackage::where('user_id', $user->id)
                ->where('status', 'complete')
                ->count();
            $endPackage = OrderPackage::where('user_id', $user->id)
                ->where('status', 'decline')
                ->count();
        }

        return response()->json([
            'message' => 'Ok',
            'user' => $user,
            'acitvePackage' => $activePackage ?? 0,
            'endPackage' => $endPackage ?? 0,
        ]);
    }
}
