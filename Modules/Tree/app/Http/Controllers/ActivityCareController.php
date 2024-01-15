<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\ActivityCare;
use Inertia\Inertia;
class ActivityCareController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-care', ['only' => ['index']]);
        $this->middleware('permission:create-care', ['only' => ['store']]);
        $this->middleware('permission:update-care', ['only' => ['update']]);
        $this->middleware('permission:delete-care', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activity = ActivityCare::paginate(15);
        return Inertia::render('Modules/Tree/Tree/ActivityCare', compact('activity'));
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
    public function store(Request $request): RedirectResponse
    {
        $activity = ActivityCare::create(['name' => $request->name]);
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
    public function update(Request $request, ActivityCare $activity): RedirectResponse
    {
        $activity->update([
            'name' => $request->name,
        ]);
        return back()->with('success', 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
