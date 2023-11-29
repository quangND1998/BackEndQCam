<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Customer\app\Models\ReviewManagement;
use App\Models\User;
use Modules\Order\app\Models\Order;
use Inertia\Inertia;
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reviews = ReviewManagement::with('user','order')->where('type','product')->orderBy('created_at','desc')->paginate(20);
        // return $reviews;
        $filters = $request->all('search');
        return Inertia::render('Modules/Order/Review/index', compact('reviews','filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('order::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('order::edit');
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