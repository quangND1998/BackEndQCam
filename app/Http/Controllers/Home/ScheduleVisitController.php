<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Customer\app\Models\ScheduleVisit;
class ScheduleVisitController extends Controller
{
    public function getAll(){
        
    }
    //state : pending, confirm, compelete, cancel
    public function getPending(Request $request){
        $filters = $request->all('search');
        $scheduleVisits = ScheduleVisit::with('product_owner_service.customer')->where('state','pending')->orderBy('created_at','desc')->paginate('20');
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits'));
    }
    public function changeState(Request $request,$id){
        $visit = ScheduleVisit::findOrFail($id);
        if($visit->state == "pending"){
            $visit->state = "confirm";
        }else if($visit->state == "confirm"){
            $visit->state = "complete";
        };
        $visit->save();
        return back()->with('success', 'xác nhận đặt lịch');
    }

    public function getConfirm(Request $request){
        $filters = $request->all('search');
        $scheduleVisits = ScheduleVisit::with('product_owner_service.customer')->where('state','confirm')->orderBy('created_at','desc')->paginate('20');
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits'));
    }
    public function getCancel(Request $request){
        $filters = $request->all('search');
        $scheduleVisits = ScheduleVisit::with('product_owner_service.customer')->where('state','cancel')->orderBy('created_at','desc')->paginate('20');
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits'));
    }
    public function getComplete(Request $request){
        $filters = $request->all('search');
        $scheduleVisits = ScheduleVisit::with('product_owner_service.customer')->where('state','complete')->orderBy('created_at','desc')->paginate('20');
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits'));
    }
}
