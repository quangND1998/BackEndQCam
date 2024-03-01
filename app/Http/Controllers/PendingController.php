<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\Remind;
use Inertia\Inertia;
class PendingController extends Controller
{
    //
    public function index(Request $request){
        $reminds = Remind::with('productServiceOwner.order_package.product_service','productServiceOwner.customer','csr')
            ->where('remind_at', '>=', now()->toDateString())
            ->paginate(10);
        // return $reminds;
        return Inertia::render('Modules/CSKH/Pending/index',compact('reminds'));
    }
}
