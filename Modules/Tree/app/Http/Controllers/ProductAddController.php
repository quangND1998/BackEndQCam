<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\ProductHistory;
use Modules\Tree\app\Models\ProductRetail;
use Inertia\Inertia;
class ProductAddController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product_retails = ProductRetail::get();
        $historyAdds = ProductHistory::with('product_retail')->paginate(10);
        return Inertia::render('Modules/Tree/ProductRetail/ProductHistory', compact('product_retails','historyAdds'));
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
    public function store(Request $request)
    {
        $productHistory = ProductHistory::create($request->all());
        $productHistory->state = 'Chưa về';
        $productHistory->state_confirm = "Chờ hàng";
        $productHistory->save();
        // $product_retail = ProductRetail::findOrFail($request->product_retail_id);
        // if($product_retail){
        //     $product_retail->available_quantity += $request->actual_quantity;
        //     $product_retail->save();
        // }
        return back()->with('success', 'Create succesfully');
    }

    public function update(Request $request, $id)
    {
        // dd($id);
        $productHistory = ProductHistory::findOrFail($id);
        $productHistory->update($request->all());
        $product_retail = ProductRetail::findOrFail($request->product_retail_id);
        if($product_retail){
            // check lai cho này, vì đã + lúc thêm r
            $product_retail->available_quantity -= $productHistory->actual_quantity;
            $product_retail->available_quantity += $request->actual_quantity;
            $product_retail->save();
        }
        return back()->with('success', 'update succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $productHistory = ProductHistory::findOrFail($id);
        $product_retail = $productHistory->product_retail;
        if($product_retail){
            // check lai cho này, vì đã + lúc thêm r
            $product_retail->available_quantity -= $productHistory->actual_quantity;
            $product_retail->save();
        }
        $productHistory->delete();
        return back()->with('success', 'remove succesfully');
    }
    public function confirm($id)
    {
        $productHistory = ProductHistory::findOrFail($id);
        $productHistory->state = 'Nhập kho';
        $productHistory->state_confirm = 'Đã xác nhận';
        $productHistory->save();
        $product_retail = $productHistory->product_retail;
        if($product_retail){
            $product_retail->available_quantity += $productHistory->actual_quantity;
            $product_retail->save();
        }
        return back()->with('success', 'confirm succesfully');
    }

}
