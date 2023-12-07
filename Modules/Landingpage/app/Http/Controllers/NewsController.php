<?php

namespace Modules\Landingpage\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Landingpage\app\Models\News;
use Inertia\Inertia;
use Illuminate\Validation\Validator;
use Modules\Landingpage\app\Http\Middleware\New\StoreRequest;
use Modules\Landingpage\app\Http\Middleware\New\UpdateRequest;
class NewsController extends Controller
{
    function __construct()
    {
        // $this->middleware('role:super-admin');
        $this->middleware('permission:view-news|create-news|delete-news|update-news', ['only' => ['index']]);
        $this->middleware('permission:create-news', ['only' => ['store']]);
        $this->middleware('permission:update-news', ['only' => ['update']]);
        $this->middleware('permission:delete-news', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::with('images')->paginate(12);
        return Inertia::render('Modules/Landingpage/News/Index', compact('news'));
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
        $new = News::create([
            'title' => $request->title,
            'link' => changeTitle($request->title),
            'state' => $request->state,
            'type' =>$request->type,
            'short_description' =>$request->short_description,
            'description' => $request->description
        ]);
        foreach ($request->images as $image) {
            $new->addMedia($image)->toMediaCollection('news_images');
        }
        return back()->with('scuccess', 'Create successfully');
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
    public function update(UpdateRequest $request, $new) : RedirectResponse
    {
     
        $new = News::findOrFail($new);
        $new->update([
            'title' => $request->title,
            'state' => $request->state,
            'type' =>$request->type,
            'short_description' =>$request->short_description,
            'description' => $request->description
        ]);
        if( $request->file('images') != null){
          
            $new->clearMediaCollection('news_images');
            
           
            $new->addMedia($request->images)->toMediaCollection('news_images');
            
        }
        return back()->with('success', 'Update successffully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $new = News::findOrFail($id);
        if($new){
        $new->clearMediaCollection('news_images');
        $new->delete();
        return back()->with('scuccess', 'Delete successfully');
        }
    }
}
