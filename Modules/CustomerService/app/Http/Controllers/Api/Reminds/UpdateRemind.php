<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Reminds;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\Remind;

class UpdateRemind extends Controller
{
    public function __invoke(Remind $remind, Request $request)
    {
        $request->validate([
            'remind_at' => 'required|date|after:today',
            'note' => 'required|string',
        ]);

        abort_if($remind->user_id != auth()->id(), 442, 'Not your remind');
        $remind->update($request->all());

        return response()->json([
            'message' => 'OK',
        ]);
    }
}
