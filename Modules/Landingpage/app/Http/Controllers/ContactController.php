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
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contact = Contact::first();
        return Inertia::render("Modules/Landingpage/Contact/Index", compact('contact'));
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

        $contact = Contact::first();
        if ($contact) {
            $contact->update([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'tax_code' => $request->tax_code,
                'hotline' => $request->hotline,
                'zalo_phone' => $request->zalo_phone,
                'facebook' => $request->facebook,
                'website' => $request->website,
                'map' => $request->map,
            ]);
        } else {
            Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'tax_code' => $request->tax_code,
                'hotline' => $request->hotline,
                'zalo_phone' => $request->zalo_phone,
                'facebook' => $request->facebook,
                'website' => $request->website,
                'map' => $request->map,
            ]);
        }

        return back()->with('success', "Lưu thành công");
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
