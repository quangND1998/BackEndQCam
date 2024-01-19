<?php

namespace Modules\Booking\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Booking\app\Models\Booking;
use Inertia\Inertia;
use App\Models\User;
use Modules\Booking\app\Models\BookingHistory;

class BookingManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Booking $booking)
    {
        $booking = Booking::with(['user','history'])->withCount([
            'history'])->find($booking->id);
        $users =  User::whereHas('team')->with('team')->role('saler')->get();
        return Inertia::render('Modules/Booking/Detail', compact('booking','users'));
    }
    public function generate(Request $request,Booking $booking){
        $this->validate(
            $request,
            [
                'number_generate' => 'required',
            ]
        );

        if($booking->last_history){
            $number_current = $booking->last_history->ballot_code;

        }else{
            $number_current = $booking->ballot_code;
        }
        // dd($booking->history[0]);
        for($i=0; $i<$request->number_generate; $i++){
            $code = new BookingHistory;
            $code->ballot_code = $number_current + 1;
            $code->status = "Chưa phát";
            $code->bookings_id = $booking->id;
            $code->save();

            $number_current ++;
        }
        return back()->with('success', 'Create successfully');
    }
    public function changeRef(Request $request,BookingHistory $code){
        //   dd($request);
        //  dd($request->ref_id[$request->index]);
         $code->ref_id = $request->ref_id[$request->index];
         $code->save();
        //  dd($code);
        return back()->with('success', 'Create successfully');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('booking::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('booking::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('booking::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
