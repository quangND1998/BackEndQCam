<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Tree\app\Http\Requests\Tree\StoreRequest;
use Modules\Tree\app\Models\Land;
use Modules\Tree\app\Models\Tree;
use Illuminate\Support\Str;
use Modules\Landingpage\app\Models\Contact;
use Modules\Tree\app\Models\ActivityCare;

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
        // $trees = Tree::with('land')->whereHas('land', function ($query) {
        //     $query->where('state', 'public');
        // })->where('state','public')->where('product_service_owner_id',null)->first();
        // dd($trees);

        $trees = Tree::with('images', 'thumb_image','history_care.activityCare')->where('land_id', $land->id)->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('qr_code', 'LIKE', '%' . $request->search . '%');
            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->paginate(5);

        $treesAll = Tree::get();

        //  return $treesAll;

        $activityCare = ActivityCare::get();
        return Inertia::render('Modules/Tree/Tree/Index', compact('land', 'trees','activityCare','treesAll'));
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
        if ($request->images_thumb) {
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
    public function update(Request $request, Tree $tree)
    {
        $user = Auth::user();
        if ($tree->product_service_owner) {
            if ($user->hasPermissionTo('super-admin')) {
                $tree->update($request->all());
                if ($request->hasFile('images')) {
                    // $tree->clearMediaCollection('tree_images');

                    foreach ($request->images as $image) {
                        $tree->addMedia($image)->toMediaCollection('tree_images');
                    }
                }
                if ($request->hasFile('images_thumb')) {
                    $tree->clearMediaCollection('tree_thumb');
                    $tree->addMedia($request->file('images_thumb'))->toMediaCollection('tree_thumb');
                }
            } else {
                $tree->update([
                    'name' => $request->name,
                    "description" => $request->description,
                    "user_manual" => $request->user_manual,
                    "terms_policy" => $request->terms_policy,

                ]);
                if ($request->hasFile('images')) {
                    $tree->clearMediaCollection('tree_images');

                    foreach ($request->images as $image) {
                        $tree->addMedia($image)->toMediaCollection('tree_images');
                    }
                }
                if ($request->hasFile('images_thumb')) {
                    $tree->clearMediaCollection('tree_thumb');
                    $tree->addMedia($request->file('images_thumb'))->toMediaCollection('tree_thumb');
                }
                return back()->with('success', 'Update successfully');
            }
        } else {
            $tree->update($request->all());
            if ($request->hasFile('images')) {
                // $tree->clearMediaCollection('tree_images');

                foreach ($request->images as $image) {
                    $tree->addMedia($image)->toMediaCollection('tree_images');
                }
            }
            if ($request->hasFile('images_thumb')) {
                $tree->clearMediaCollection('tree_thumb');
                $tree->addMedia($request->file('images_thumb'))->toMediaCollection('tree_thumb');
            }
        }

        return back()->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tree $tree)
    {

        if ($tree->product_service_owner) {
            return back()->with('warning', 'Cây cam đã được nhận nuôi bởi khách hàng, Bạn nên xóa hợp đồng trước khi xóa cây');
        }
        $tree->clearMediaCollection('tree_images');
        $tree->clearMediaCollection('tree_thumb');
        $tree->delete();
        return back()->with('success', 'Delete successfully');
    }
    public function treeDetail(Request $request, $product_owner_id)
    {
        $tree = Tree::with('land', 'thumb_image', 'images', 'product_service_owner.customer')->where('product_service_owner_id', $product_owner_id)->first();
        $contact = Contact::find(1);
        if ($tree) {
            // return $tree;
            return Inertia::render('Modules/Tree/Tree/QrTree', compact('contact', 'tree'));
        }
        return response()->json('Không tìm thấy cây nào', 404);
    }
}
