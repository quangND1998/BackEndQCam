<?php

namespace Modules\CustomerService\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\ScheduleVisit;

class CreateVisit extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'product_service_owner_id' => 'required|integer|exists:product_service_owners,id',
            'date_time' => 'required|date|after:today',
            'number_adult' => 'required|integer|min:1',
            'number_children' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $productServiceOwner = ProductServiceOwner::findOrFail($request->product_service_owner_id);
        abort_if($productServiceOwner->user_id != $request->customerId, 442, 'Customer and serivce mismatch');

        $request->merge([
            'state' => 'pending'
        ]);
        $scheduleVisit = ScheduleVisit::create($request->all());

        return response()->json([
            'message' => 'OK',
            'scheduleVisit' => $scheduleVisit
        ]);
    }
}
