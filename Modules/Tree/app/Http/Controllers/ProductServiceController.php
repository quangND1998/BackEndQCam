<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Tree\app\Http\Requests\ProductService\StoreRequest;
use Modules\Tree\app\Http\Requests\ProductService\UpdateRequest;
use Modules\Tree\app\Models\ProductService;

class ProductServiceController extends Controller
{
    protected $allowStoreField = [
        'name', 'number_tree', "acreage",  "free_visit", "amount_products_received", "price",    "number_deliveries", "life_time", "description", "number_receive_product",   "unit","transfer_value","price_origin"
    ];
    public function __construct()
    {
        $this->middleware('permission:view-product', ['only' => ['index']]);
        $this->middleware('permission:create-product', ['only' => ['store']]);
        $this->middleware('permission:update-product', ['only' => ['update']]);
        $this->middleware('permission:delete-product', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $product_services = ProductService::with('images')->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');

            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->paginate(15);

        return Inertia::render('Modules/Tree/ProductService/Index', compact('product_services'));
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
        $data = $request->only($this->allowStoreField);
        $product_service = ProductService::create($data);
        foreach ($request->images as $image) {
            $product_service->addMedia($image)->toMediaCollection('product_service_images');
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
    public function update(UpdateRequest $request, ProductService $product_service): RedirectResponse
    {
        $data = $request->only($this->allowStoreField);

        $product_service->update($data);
        // dd($request);
        if( $request->file('images') != null){
            $product_service->clearMediaCollection('product_service_images');
            foreach ($request->images as $image) {
                $product_service->addMedia($image)->toMediaCollection('product_service_images');
            }
        }
        return back()->with('success', 'Update succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductService $product_service)
    {
        if(count($product_service->productServiceOwner) >0){
            return back()->with('warning', 'Gói dịch vụ đã được gán quyền sử dụng cho khách hàng, bạn cần xoá tất cả đơn hàng trước khi xoá gói dịch vụ');
        }

        $product_service->clearMediaCollection('product_service_images');
        $product_service->delete();
        return back()->with('success', 'Delete succesfully');
    }


    public function changeStatus(Request $request)
    {
        $product_service = ProductService::findOrFail($request->id);
        $product_service->update(['status' => $request->status]);
        return back()->with('success', 'Update Status successfully');
    }
}
