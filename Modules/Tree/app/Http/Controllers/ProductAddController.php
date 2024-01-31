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
        return back()->with('success', 'Create succesfully');
    }

    public function update(Request $request, $id)
    {
        // dd($id);
        $productHistory = ProductHistory::findOrFail($id);
        $productHistory->update($request->all());
        return back()->with('success', 'update succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
