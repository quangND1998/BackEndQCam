<?php

namespace Modules\Landingpage\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Landingpage\app\Http\Requests\Contact\StoreRequest;
use Modules\Landingpage\app\Http\Requests\Contact\UpdateRequest;
use Modules\Landingpage\app\Models\Contact;
class ContactController extends Controller
{ /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
       $contract= Contact::first();
       return Inertia::render("",compact('contract'));
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
       $Contract= Contact::create($request->all());
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
       
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(UpdateRequest $request, Contact $contact): RedirectResponse
   {
       $contact->update($request->all());
       return back()->with('success', "Cập nhật thành công");
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Contact $contact)
   {
       $contact->delete();
       return back()->with('success', "Xóa thành công");
   }
}
