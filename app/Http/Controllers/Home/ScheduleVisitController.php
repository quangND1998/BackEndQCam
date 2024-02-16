<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\ScheduleVisit;
use Illuminate\Support\Facades\DB;
class ScheduleVisitController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create-schedule', ['only' => ['createShedule']]);
    }
    public function getAll(Request $request)
    {
        $filters = $request->all('search');
        $scheduleVisits = $this->getVisitSchedule('',$request);
        $statusGroup = $this->groupByStatus();
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits','statusGroup'));
    }
    //state : pending, confirm, compelete, cancel
    public function getPending(Request $request)
    {
        $filters = $request->all('search');
        $scheduleVisits = $this->getVisitSchedule('pending',$request);        // return $scheduleVisits;
        $statusGroup = $this->groupByStatus();
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits','statusGroup'));
    }
    public function changeState(Request $request, $id)
    {
        $visit = ScheduleVisit::findOrFail($id);
        if ($visit->state == "pending") {
            $visit->state = "confirm";
        } else if ($visit->state == "confirm") {
            $visit->state = "complete";
        };
        $visit->save();
        return back()->with('success', 'xác nhận đặt lịch');
    }
    public function cancelState(Request $request, $id)
    {
        $visit = ScheduleVisit::findOrFail($id);
        $visit->state = "cancel";
        $visit->save();
        return back()->with('success', 'xác nhận đặt lịch');
    }

    public function getConfirm(Request $request)
    {
        $filters = $request->all('search');
        $scheduleVisits = ScheduleVisit::with('product_owner_service.customer')->where('state', 'confirm')->orderBy('created_at', 'desc')->paginate('20');
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits'));
    }
    public function getCancel(Request $request)
    {
        $filters = $request->all('search');
        $scheduleVisits = $this->getVisitSchedule('cancel',$request);        // return $scheduleVisits;
        $statusGroup = $this->groupByStatus();
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits','statusGroup'));
    }
    public function getComplete(Request $request)
    {
        $filters = $request->all('search');
        $state = 'complete';
        $scheduleVisits = $this->getVisitSchedule($state,$request);        // return $scheduleVisits;
        $statusGroup = $this->groupByStatus();
      //  return $scheduleVisits;
        return Inertia::render('Home/visit/getPending', compact('filters', 'scheduleVisits','statusGroup'));
    }


    public function createShedule(Request $reuqest)
    {
        $customers = User::with(['product_service_owners.trees', 'product_service_owners.product', 'product_service_owners' => function ($q) {
            $q->where('state', 'active');
        }])->role('customer')->get();
        return Inertia::render('Home/visit/Create', compact('customers'));
    }


    public function saveShedule(Request $request)
    {
        $this->validate(
            $request,
            [
                'date_time' => 'required|date|after:tomorrow',
                'number_adult' => 'required|gt:0',
                'number_children' => 'nullable|gt:-1',
                'product_service_owner_id' => 'required',
            ]
        );


        $product_owner = ProductServiceOwner::findOrFail($request->product_service_owner_id);
        if ($product_owner->visited_time < $product_owner->product->free_visit) {
            $date_time = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');
            $visit = ScheduleVisit::create($request->all());
            $visit->state = "pending";
            $visit->date_time = $date_time;
            $visit->save();

            return redirect()->route('visit.pending')->with('success', 'Đã đặt lịch thành công');
        } else {
            return back()->with('warning','Không còn lượt đặt lịch');
        }
    }

    public function edit(ScheduleVisit $schedule){
        if($schedule->state =='pending')
        {
            return Inertia::render('Home/visit/Update', compact('schedule'));
        }
        return back()->with('warning', 'Lịch đặt này không thể cập nhật');
    }
    public function updateShedule(Request $request, ScheduleVisit $schedule)
    {

        $this->validate(
            $request,
            [
                'date_time' => 'required|date|after:tomorrow',
                'number_adult' => 'required|gt:0',
                'number_children' => 'nullable|gt:-1',

            ]
        );
        $schedule->update([
            'date_time' => $request->date_time,
            'number_adult' => $request->number_adult,
            'number_children' => $request->number_children,
        ]);

        return redirect()->route('visit.pending')->with('success', 'Cập nhật đặt lịch thành công');

    }
    public function groupByStatus()
    {
        $array_status = ['pending','complete', 'cancel'];

        $datas = ScheduleVisit::select('state', DB::raw('count(*) as total'));
        $statusGroup = $datas->groupBy('state')->get();


        foreach ($array_status as $status) {

            $filtered = $statusGroup->where('state', $status)->first();

            if ($filtered == null) {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => 0,
                );
            } else {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => $filtered->total,
                );

            }
        }
        $newCollections[] = array(
            'status' => 'all',
            'total' => ScheduleVisit::all()->count(),
        );

        return $newCollections;
    }
    public function getVisitSchedule($state,$request){
        // dd($state);
        $scheduleVisits = ScheduleVisit::with('extraServices','product_owner_service.customer','product_owner_service.order_package')
        ->orderBy('created_at', 'desc')
        ->fillter($request->only('search','from', 'to'));

        if($state != null){
            $scheduleVisits = $scheduleVisits->where('state', $state);

        }
        $datas = $scheduleVisits->paginate('20');
        
        return $datas;

    }
}
