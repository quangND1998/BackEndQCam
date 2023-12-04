<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Inertia\Inertia;
use Modules\Order\app\Models\Order;

class CustomerActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:package-custommer', ['only' => ['index', 'gift']]);
    }
    public function index($id)
    {
        $customer = User::findOrFail($id);
        return Inertia::render('Modules/Customer/detail/activity', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function gift($id){
        $customer = User::findOrFail($id);
        $gifts = Order::with('orderItems.product','product_service.product')->whereHas('product_service')->where('user_id',$customer->id)->paginate(20);
        //  return $gifts;
        return Inertia::render('Modules/Customer/detail/gift', compact('customer','gifts'));
    }
    public function create()
    {
        return view('customer::create');
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
        return view('customer::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('customer::edit');
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
