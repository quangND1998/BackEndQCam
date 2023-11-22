<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Tree\app\Http\Requests\Tree\StoreRequest;
use Modules\Tree\app\Models\Land;
use Modules\Tree\app\Models\Tree;
use Illuminate\Support\Str;
use Modules\Landingpage\app\Models\Contact;

class TreeController extends Controller
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
    public function index(Request $request, Land $land)
    {
        $trees = Tree::with('images','thumb_image')->where('land_id', $land->id)->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('qr_code', 'LIKE', '%' . $request->search . '%');
            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->paginate(12);

        // return $trees;
        return Inertia::render('Modules/Tree/Tree/Index', compact('land', 'trees'));
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
    public function store(StoreRequest $request, Land $land): RedirectResponse
    {

        $tree = Tree::create($request->all());
        if ($tree) {
            $tree->qr_code = $tree->id . Str::random(10);
            $tree->land_id = $land->id;
            $tree->save();
        }

        foreach ($request->images as $image) {
            $tree->addMedia($image)->toMediaCollection('tree_images');
        }
        if($request->images_thumb){
            $tree->addMedia($request->images_thumb)->toMediaCollection('tree_thumb');
        }
        return back()->with('success', 'Create successfully');
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
    public function update(Request $request, Tree $tree): RedirectResponse
    {
        $tree->update($request->all());
        if ($request->hasFile('images')) {
            $tree->clearMediaCollection('tree_images');

            foreach ($request->images as $image) {
                $tree->addMedia($image)->toMediaCollection('tree_images');
            }

        }
        if($request->hasFile('images_thumb')){
            $tree->clearMediaCollection('tree_thumb');
            $tree->addMedia($request->images_thumb)->toMediaCollection('tree_thumb');
        }
        return back()->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tree $tree)
    {
        $tree->clearMediaCollection('tree_images');
        $tree->clearMediaCollection('tree_thumb');
        $tree->delete();
        return back()->with('success', 'Delete successfully');
    }
    public function treeDetail(Request $request,$product_owner_id){
        $tree = Tree::with('land','thumb_image','images','product_service_owner.customer')->where('product_service_owner_id',$product_owner_id)->first();
        $contact = Contact::find(1);
        if($tree){
            // return $tree;
            return Inertia::render('Modules/Tree/Tree/QrTree', compact('contact','tree'));
        }
        return response()->json('Không tìm thấy cây nào', 404);
    }
}
