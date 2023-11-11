<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Customer\app\Models\ScheduleVisit;
class ScheduleVisitController extends Controller
{
    public function getPending(Request $request){
        $filters = $request->all('search');
        $scheduleVisits = ScheduleVisit::with('product_owner_service.customer')->orderBy('created_at','desc')->paginate('20');
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits'));
    }
}
