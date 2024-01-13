<?php

namespace Modules\CustomerService\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ScheduleVisit;

class UpdateVisit extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'date_time' => 'required|date|after:today',
            'number_adult' => 'required|integer|min:1',
            'number_children' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $scheduleVisit = ScheduleVisit::findOrFail($request->scheduleVisitId);
        $scheduleVisit->update($request->all());

        return response()->json([
            'message' => 'OK',
            'scheduleVisit' => $scheduleVisit
        ]);
    }
}
