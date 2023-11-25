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
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
    public function listDocument($id){
         $customer = User::findOrFail($id);

        $listData=['order_related_images','order_package_images','contract_images'];
        $orderFiles = Media::whereIn('collection_name',$listData)->get();
        //  dd($orderFiles);
        // // orders
        // // hop dong
        // return $customer;

        return Inertia::render('Modules/Customer/detail/document', compact('customer','orderFiles'));
    }

}
