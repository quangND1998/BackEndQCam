<?php

namespace Modules\Booking\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Booking\app\Models\Booking;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Booking\app\Models\BookingHistory;

class BookingManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Booking $booking)
    {
        $booking = Booking::with(['user', 'history' => function ($q) use ($request) {
            $q->fillter($request->only('fromdate', 'toDate', 'status','search'))->get();
        }])->withCount(['history'])->find($booking->id);
        $users =  User::whereHas('team')->with('team')->role('saler')->get();
        $statusGroup = $this->groupBookingStatus($booking);
        return Inertia::render('Modules/Booking/Detail', compact('booking', 'users','statusGroup'));
    }
    public function generate(Request $request, Booking $booking)
    {
        $this->validate(
            $request,
            [
                'number_generate' => 'required',
            ]
        );

        if ($booking->last_history) {
            $number_current = $booking->last_history->ballot_code;
        } else {
            $number_current = $booking->ballot_code;
        }
        // dd($booking->history[0]);
        for ($i = 0; $i < $request->number_generate; $i++) {
            $code = new BookingHistory;
            $code->ballot_code = $number_current + 1;
            $code->status = "Chưa phát";
            $code->bookings_id = $booking->id;
            $code->save();

            $number_current++;
        }
        return back()->with('success', 'Create successfully');
    }
    public function changeRef(Request $request, BookingHistory $code)
    {
        // return $request->ref_id[$request->index];
        $this->validate(
            $request,
            [
                'index' => 'required',
                'ref_id' => 'required',
            ]
        );

        if($request->status == "huy"){
            $code->ref_id = null;
            $code->dateStart = null;
            $code->dateEnd = null;
        }else{
            $code->ref_id = $request->ref_id[$request->index];
            $code->dateStart = Carbon::now();
        }

        $code->save();
        return $code;
        return back()->with('success', 'Create successfully');
    }
    public function changeStatus(Request $request, BookingHistory $code)
    {
        $this->validate(
            $request,
            [
                'index' => 'required',
                'ref_id' => 'required',
                'status' => 'required',
            ]
        );
        $code->status = $request->status;
        if ($request->status == "Đang phát") {
            $code->dateStart = Carbon::now();
        }
        if ($request->status == "Đã thu hồi") {
            $code->dateEnd = Carbon::now();
        }
        if ($request->status == "Đã hủy") {
            $code->reason = $request->reason;
        }
        $code->save();
        return back()->with('success', 'Create successfully');
    }
    public function changeStatusAll(Request $request, Booking $booking){
        $this->validate(
            $request,
            [
                'status' => 'required',
            ]
        );
        foreach($booking->history as $code){
            if($request->status == "phát"){
                if($code->ref_id != null && $code->status == "Chưa phát"){
                    $code->status = "Đang phát";
                    $code->dateStart = Carbon::now();
                    $code->save();
                }
            }else{
                if($code->ref_id != null && $code->status == "Đang phát"){
                    $code->status = "Đã thu hồi";
                    $code->dateEnd = Carbon::now();
                    $code->save();
                }
            }

        }
        return back()->with('success', 'Create successfully');
    }
    public function groupBookingStatus($booking)
    {
        $array_status = ['Chưa phát', 'Đang phát', 'Đã thu hồi', 'Đã hủy'];
        $statusGroup = BookingHistory::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->where('bookings_id', $booking->id)
            ->get();
        foreach ($array_status as $status) {
            $filtered = $statusGroup->where('status', $status)->first();
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
        return $newCollections;
    }
    public function printData(Request $request, Booking $booking){
        $booking = Booking::with(['user','last_history','history.ref', 'history' => function($q){
            $q->whereIn('status',['Đang phát','Chưa phát']);
        }])->withCount(['history'])->find($booking->id);
        return Inertia::render('Modules/Booking/Print', compact('booking'));
    }
}
