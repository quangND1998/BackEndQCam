<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Tree\app\Models\ProductService;
use Modules\Tree\app\Models\ProductRetail;

class CustomerDetailController extends Controller
{
    function __construct()
    {
        $this->middleware('role:super-admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function info($id)
    {
        $customer = User::findOrFail($id);
        return Inertia::render('Modules/Customer/detail/info', compact('customer'));
    }
    public function updateinfo($id)
    {
        $customer = User::findOrFail($id);
        return Inertia::render('Modules/Customer/detail/info', compact('customer'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function products($id)
    {
        $customer = User::with('product_service_owners.product')->whereHas('roles', function ($query) {
                $query->where('name', 'Customer');
            })->findOrFail($id);
        // return $customer;
        $products = ProductService::where("status", 1)->get();
        return Inertia::render('Modules/Customer/detail/products', compact('customer','products'));
    }
    public function activity($id)
    {
        $customer = User::findOrFail($id);
        return Inertia::render('Modules/Customer/detail/activity', compact('customer'));
    }
    public function service($id)
    {
        $customer = User::findOrFail($id);
        return Inertia::render('Modules/Customer/detail/service', compact('customer'));
    }

}
