<?php

namespace Modules\Landingpage\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Landingpage\app\Http\Requests\QuestionAnswer\StoreRequest;
use Modules\Landingpage\app\Http\Requests\QuestionAnswer\UpdateRequest;
use Modules\Landingpage\app\Models\QuestionAnswer;


class QuestionAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $question_answers= QuestionAnswer::where(function ($query) use ($request) {
            $query->where('question', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('answer', 'LIKE', '%' . $request->search . '%');
            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->paginate(20)->appends($request->search);
        return Inertia::render("",compact('question_answers'));
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
        $question= QuestionAnswer::create($request->all());
        return back()->with('success', "Create succesffuly");
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, QuestionAnswer $question): RedirectResponse
    {
        $question->update($request->all());
        return back()->with('success', "Update succesffuly");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionAnswer $question)
    {
        $question->delete();
        return back()->with('success', "Delete succesffuly");
    }
}
