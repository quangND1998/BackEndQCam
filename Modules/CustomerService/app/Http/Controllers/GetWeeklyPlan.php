<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\CustomerService\app\Models\Remind;

class GetWeeklyPlan extends Controller
{
    public function __invoke(Request $request)
    {
        $remindData = Remind::where('user_id', auth()->id())
            ->with('productServiceOwner', function ($query) {
                $query->with(['order_package.product_service', 'customer']);
            })
            ->where('remind_at', '>=', now()->toDateString())
            ->paginate(3);

        return Inertia::render('Modules/CustomerService/weekly-plan', compact(
            'remindData'
        ));
    }
}
