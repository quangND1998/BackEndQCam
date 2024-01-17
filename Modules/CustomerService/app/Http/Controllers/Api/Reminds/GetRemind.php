<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Reminds;

use App\Http\Controllers\Controller;
use Modules\CustomerService\app\Models\Remind;

class GetRemind extends Controller
{
    public function __invoke()
    {
        $reminds = Remind::where('user_id', auth()->id())
            ->where('remind_at', '>=', now()->toDateString())
            ->get();

        return response()->json([
            'reminds' => $reminds
        ]);
    }
}
