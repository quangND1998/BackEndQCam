<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\ScheduleVisits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ScheduleVisit;

class UpdateVisit extends Controller
{
    public function __invoke($doNotRemoveThisUnsusedVariable, ScheduleVisit $scheduleVisit, Request $request)
    {
        $request->validate([
            'date_time' => 'required|date|after:today',
            'number_adult' => 'required|integer|min:1',
            'number_children' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'services' => 'nullable|array|exists:visit_extra_services,id',
            'state' => 'required|string|in:pending,cancel,complete'
        ]);

        $scheduleVisit->update($request->all());
        $scheduleVisit->extraServices()->sync($request->services);

        return response()->json([
            'message' => 'OK',
        ]);
    }
}
