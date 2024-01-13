<?php

namespace Modules\Tree\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\ActivityCare;
use Modules\Tree\app\Models\HistoryCare;
use Modules\Tree\app\Models\Land;
use Modules\Tree\app\Models\Tree;
class HistoryCareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tree::index');
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
    public function store(Request $request, Tree $tree): RedirectResponse
    {
        $this->validate(
            $request,
            [
                'date' => 'required',
                'selectedActivity' => 'required'
            ]
        );
        // return "ngaaaa";
        $historyCare = new HistoryCare;
        $historyCare->date = $request->date;
        $historyCare->trees_id = $tree->id;
        $historyCare->save();

        $activities = ActivityCare::find($request->selectedActivity);

        $historyCare->activityCare()->attach($activities);

        // $tree = Tree::with('history_care.activityCare')->find($tree->id);
        // return $tree;
        return back()->with('success', 'Create successfully');
    }
    public function storeLand(Request $request){

        $this->validate(
            $request,
            [
                'land' => 'required',
                'date' => 'required',
                'selectedActivity' => 'required'
            ]
        );
        if(count($request->trees) > 0){
            $trees = Tree::find($request->trees);
        }else{
            $land = Land::with('trees')->findOrFail($request->land);
            $trees = $land->trees;
        }

        foreach($trees as $tree){
            $historyCare = new HistoryCare;
            $historyCare->date = $request->date;
            $historyCare->trees_id = $tree->id;
            $historyCare->save();

            $activities = ActivityCare::find($request->selectedActivity);
            $historyCare->activityCare()->attach($activities);
        }
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
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
