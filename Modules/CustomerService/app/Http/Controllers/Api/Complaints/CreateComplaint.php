<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Complaints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ComplaintManagement;
use Modules\Order\app\Models\OrderPackage;

class CreateComplaint extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'severity' => 'required|in:normal,urgent,critical',
            'role_id' => 'required|exists:roles,id',
            'package_id' => 'required|exists:order_packages,idPackage',
        ]);

        $number = 10000;
        $orderPackage = OrderPackage::where('idPackage', $request->package_id)->with('product_service_owner')->firstOrFail();
        $productServiceOwnerId = $orderPackage->product_service_owner->id;
        $request->merge([
            'user_id' => $request->customerId,
            'type' => 'cskh',
            'state' => 0,
            'counselor_staff_id' => auth()->id(),
            'product_service_owner_id' => $productServiceOwnerId,
            'code' => 'KN' . ($number + ComplaintManagement::count() + 1),
        ]);

        $complaint = ComplaintManagement::create(
            $request->only([
                'user_id',
                'description',
                'severity',
                'role_id',
                'type',
                'state',
                'counselor_staff_id',
                'product_service_owner_id',
                'code',
            ])
        );

        return response()->json([
            'message' => 'Ok',
            'complaint' => $complaint,
        ]);
    }
}
