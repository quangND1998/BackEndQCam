<?php

namespace Modules\Landingpage\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Landingpage\app\Http\Requests\TermsCondition\StoreRequest;
use Modules\Landingpage\app\Http\Requests\TermsCondition\UpdateRequest;
use Modules\Landingpage\app\Models\TermsConditions;

class TermsConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms_condition= TermsConditions::first();
       
        return Inertia::render("Modules/Landingpage/Terms/Index", compact('terms_condition'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('landingpage::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        TermsConditions::create($request->all());
        return back()->with('success', "Tạo mới thành công");
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('landingpage::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request,TermsConditions $term): RedirectResponse
    {
        $term->update($request->all());
        return back()->with('success', "Cập nhật thành công");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TermsConditions $term)
    {
        $term->delete();
        return back()->with('success', "Xóa thành công");
    }
}
