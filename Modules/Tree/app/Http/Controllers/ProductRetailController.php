<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Tree\app\Http\Requests\ProductRetail\StoreRequest;
use Modules\Tree\app\Models\ProductRetail;
use Illuminate\Support\Str;

class ProductRetailController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view-product', ['only' => ['index']]);
        $this->middleware('permission:create-product', ['only' => ['store']]);
        $this->middleware('permission:update-product', ['only' => ['update']]);
        $this->middleware('permission:delete-product', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        $product_retails = ProductRetail::with('images')->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('code', 'LIKE', '%' . $request->search . '%');
            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->paginate(15);

        return Inertia::render('Modules/Tree/ProductRetail/Index', compact('product_retails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tree::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $product_retail = ProductRetail::create($request->all());
        if ($product_retail) {
            $product_retail->code = $product_retail->id . Str::random(10);
            $product_retail->save();
        }
        foreach ($request->images as $image) {
            $product_retail->addMedia($image)->toMediaCollection('product_retail_images');
        }
        return back()->with('success', 'Create succesfully');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('tree::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('tree::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductRetail $product_retail): RedirectResponse
    {
        $product_retail->update($request->all());
        if ($request->hasFile('images')) {
            $product_retail->clearMediaCollection('product_retail_images');

            foreach ($request->images as $image) {
                $product_retail->addMedia($image)->toMediaCollection('product_retail_images');
            }
        }
        return back()->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductRetail $product_retail)
    {
        $product_retail->clearMediaCollection('product_retail_images');
        $product_retail->delete();
        return back()->with('success', 'Delete successfully');
    }


    public function changeStatus(Request $request)
    {
        $product_retail = ProductRetail::findOrFail($request->id);
        $product_retail->update(['status' => $request->status]);
        return back()->with('success', 'Update Status successfully');
    }
}
