<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media as ModelsMedia;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Customer\app\Models\Contract;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderPackage;
use Modules\Tree\app\Models\ProductService;
use Modules\Tree\app\Models\ProductRetail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\DB;
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
        $customer = User::find($id);

        $images = [];

        $product_owners = ProductServiceOwner::with('history_extend.contract.history_contact.images')
        ->whereHas('history_extend.contract.history_contact.images')
        ->where('user_id',$customer->id)
        ->get();

        foreach($product_owners as $product_owner){
            foreach($product_owner->history_extend as $extend ){
                if($extend->contract != null){
                    foreach($extend->contract->history_contact as $history_contact){
                        foreach($history_contact->images as $image){
                            $images[] = $image;
                        }

                    }
                }

            }
        }

        $order_retail = Order::with('order_related_images')->whereHas('order_related_images')->where('user_id',$customer->id)->get();
        foreach($order_retail as $order){
            foreach($order->order_related_images as $image2){
                $images[] = $image2;
            }
        }

        $order_services = OrderPackage::with('order_package_images')->whereHas('order_package_images')->where('user_id',$customer->id)->get();
        foreach($order_services as $order_services){
            foreach($order_services->order_package_images as $image3){
                $images[] = $image3;
            }
        }
        // $images = json_encode($images);

        //return $images;

        // $listData=['order_related_images','order_package_images','contract_images'];
        // $orderFiles = Media::whereIn('collection_name',$listData)->get();
        // dd($orderFiles);
        // // orders
        // // hop dong


        return Inertia::render('Modules/Customer/detail/document', compact('customer','images'));
    }

}
