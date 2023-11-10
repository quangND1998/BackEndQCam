<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Order\app\Http\Requests\Voucher\StoreVoucherReuquest;
use Modules\Order\app\Http\Requests\Voucher\UpdateVoucherReuquest;
use Modules\Order\app\Models\Voucher;
use Modules\Tree\app\Models\ProductRetail;

class VoucherController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view-land', ['only' => ['index', 'voucher_project']]);
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
        return Inertia::render('Modules/Voucher/Index', compact('vouchers'));
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
        $voucher = Voucher::create([
            'code' => $request->code,
            'name' =>  $request->name,
            'description' =>  $request->description,
            'type' =>  $request->type,
            'unit' =>  $request->unit,
            'is_fixed' =>  $request->is_fixed,
            'discount_amount' =>  $request->discount_amount,
            'starts_at' => $request->starts_at,
            'expires_at' =>  $request->expires_at
        ]);
        return back()->with('success', 'Create successfully');
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
    public function update(UpdateVoucherReuquest $request, Voucher $voucher): RedirectResponse
    {
        $voucher->update([
            'code' => $request->code,
            'name' =>  $request->name,
            'description' =>  $request->description,
            'type' =>  $request->type,
            'unit' =>  $request->unit,
            'is_fixed' =>  $request->is_fixed,
            'discount_amount' =>  $request->discount_amount,
            'starts_at' => $request->starts_at,
            'expires_at' =>  $request->expires_at
        ]);
        return back()->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->products()->detach();
        $voucher->delete();
        return back()->with('success', 'Delete successfully');
    }


    public function voucher_project(Request $request, Voucher $voucher)
    {
        $voucher->load('products');
        $products = ProductRetail::where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        })->paginate(1);
        if ($request->wantsJson()) {
            return $products;
        }
        return Inertia::render('Modules/Voucher/Products', compact('voucher', 'products'));
    }
}
