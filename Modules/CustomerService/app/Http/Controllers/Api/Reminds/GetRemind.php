<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Reminds;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\Remind;

class GetRemind extends Controller
{
    public function __invoke(Request $request)
    {
        $reminds = Remind::where('user_id', auth()->id())
            ->where('remind_at', '>=', now()->toDateString())
            ->paginate($request->per_page);

        return response()->json([
            'reminds' => $reminds
        ]);
    }
}
