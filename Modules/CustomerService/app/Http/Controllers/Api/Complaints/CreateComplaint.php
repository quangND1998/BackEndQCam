<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Complaints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ComplaintManagement;

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

        $orderPackage = OrderPackage::where('idPackage', $request->package_id)->with('product_service_owner')->firstOrFaild();
        $productServiceOwnerId = $orderPackage->product_service_owner->id;
        $request->merge([
            'user_id' => $request->customerId,
            'type' => 'cskh',
            'state' => 0,
            'counselor_staff_id' => auth()->id(),
            'product_service_owner_id' => $productServiceOwnerId,
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
            ])
        );

        return response()->json([
            'message' => 'Ok',
            'complaint' => $complaint,
        ]);
    }
}
