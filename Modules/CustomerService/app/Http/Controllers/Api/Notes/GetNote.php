<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Notes;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetNote extends Controller
{

    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->customerId);

        return response()->json([
            'note' => $user->note
        ]);
    }
}
