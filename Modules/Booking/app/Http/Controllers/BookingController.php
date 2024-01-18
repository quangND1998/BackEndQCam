<?php

namespace Modules\Booking\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Booking\app\Models\Booking;
use App\Models\User;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // $this->middleware('permission:view-booking', ['only' => ['index']]);
        // $this->middleware('permission:create-booking', ['only' => ['store']]);
        // $this->middleware('permission:update-booking', ['only' => ['update']]);
        // $this->middleware('permission:delete-booking', ['only' => ['destroy']]);
    }
    public function index()
    {
        $bookings = Booking::withCount([
            'history', 'history AS booking_owner_count' => function ($query) {
                $query->whereNotNull('ref_id');
            }
        ])->paginate(12);
        $users = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'Event Không được duyệt');
            }
        )->get();
        // return $bookings;
        return Inertia::render('Modules/Booking/Index', compact('bookings','users'));
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
        Booking::create($request->all());
        return back()->with('success', 'Create successfully');
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
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->all());
        return back()->with('success', 'Create successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {

        // if ($booking->product_service_owner) {
        //     return back()->with('warning', 'Dầu phiếu đang phát chưa được thu về');
        // }
        $booking->delete();
        return back()->with('success', 'Delete successfully');
    }
}
