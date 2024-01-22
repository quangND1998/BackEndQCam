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
        ]);

        $request->merge([
            'user_id' => $request->customerId,
            'type' => 'cskh',
            'state' => 0,
        ]);
        $complaint = ComplaintManagement::create(
            $request->only([
                'user_id',
                'description',
                'severity',
                'role_id',
                'type',
                'state',
            ])
        );

        return response()->json([
            'message' => 'Ok',
            'complaint' => $complaint,
        ]);
    }
}
