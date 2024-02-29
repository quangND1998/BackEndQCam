<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\Product_Fail;
use Modules\Tree\app\Models\ProductRetail;
use Inertia\Inertia;
class ProductFailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product_retails = ProductRetail::get();
        $product_fails = Product_Fail::paginate(10);
        return Inertia::render('Modules/Tree/ProductRetail/FailProduct', compact('product_retails','product_fails'));
    }

    public function getProduct(){
        $product_fails = Product_Fail::paginate(10);
        return $product_fails;
    }
    public function store(Request $request)
    {
        $product_fail = Product_Fail::create($request->all());
        return back()->with('success', 'Create succesfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product_fail = Product_Fail::findOrFail($id);
        $product_fail->delete();
        return back()->with('success', 'remove succesfully');
    }
}
