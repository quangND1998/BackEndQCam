<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Order\app\Http\Requests\Voucher\StoreVoucherReuquest;
use Modules\Order\app\Models\Voucher;
use Modules\Tree\app\Models\ProductRetail;

class VoucherController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view-land', ['only' => ['index']]);
        $this->middleware('permission:create-land', ['only' => ['store']]);
        $this->middleware('permission:update-land', ['only' => ['update']]);
        $this->middleware('permission:delete-land', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::paginate(15);
        $products = ProductRetail::get();
        return Inertia::render('', compact('vouchers', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherReuquest $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('order::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('order::edit');
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
