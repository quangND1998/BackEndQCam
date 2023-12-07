<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Tree\app\Http\Requests\Land\StoreRequest;
use Modules\Tree\app\Http\Requests\Land\UpdateRequest;
use Modules\Tree\app\Models\Land;

class LandController extends Controller
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

        // $lands = Land::paginate(12);
        $lands = Land::withCount([
            'trees', 'trees AS tree_owner_count' => function ($query) {
                $query->whereNotNull('product_service_owner_id');
            }
        ])->paginate(12);
        //  return $lands;
        return Inertia::render('Modules/Tree/Land/Index', compact('lands'));
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

        Land::create($request->all());
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
    public function update(UpdateRequest $request, Land $land): RedirectResponse
    {
        $land->update($request->all());
        return back()->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Land $land)
    {
        if($land->state !== 'public'){
            $land->delete();
            return back()->with('success', 'Delete successfully');
        }
        return back()->with('warning', 'Lô đang mở bản ko xóa');
    }
}
