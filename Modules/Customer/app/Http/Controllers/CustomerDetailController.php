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
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class CustomerDetailController extends Controller
{
    use FileUploadTrait;
    function __construct()
    {
        // $this->middleware('role:super-admin');
        $this->middleware('permission:view-user|create-user|delete-user|update-user|info-customer', ['only' => ['index']]);
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
    public function listDocument($id)
    {
        $customer = User::find($id);

        $images = [];

        $product_owners = ProductServiceOwner::with('history_extend.contract.history_contact.images')
            ->whereHas('history_extend.contract.history_contact.images')
            ->where('user_id', $customer->id)
            ->get();

        foreach ($product_owners as $product_owner) {
            foreach ($product_owner->history_extend as $extend) {
                if ($extend->contract != null) {
                    foreach ($extend->contract->history_contact as $history_contact) {
                        foreach ($history_contact->images as $image) {
                            $images[] = $image;
                        }
                    }
                }
            }
        }

        $order_retail = Order::with('order_related_images')->whereHas('order_related_images')->where('user_id', $customer->id)->get();
        foreach ($order_retail as $order) {
            foreach ($order->order_related_images as $image2) {
                $images[] = $image2;
            }
        }

        $order_services = OrderPackage::with('order_package_images')->whereHas('order_package_images')->where('user_id', $customer->id)->get();
        foreach ($order_services as $order_services) {
            foreach ($order_services->order_package_images as $image3) {
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


        return Inertia::render('Modules/Customer/detail/document', compact('customer', 'images'));
    }
    public function viewUpdateInfor(User $customer)
    {
        $info = $customer->infor;
        if ($info && $info->status == 0) {
            return Inertia::render('Modules/Customer/Infor', compact('customer', 'info'));
        }
        return back()->with('warning', "Thông tin cập nhật không có hoặc đã xét duyệt!");
    }
    public function approInfo(User $user)
    {

        if ($user->infor && $user->infor->status == 0) {
            $new_info = $user->infor;
            $user->update([
                'name' => $new_info->name,
                'address' => $new_info->address,
                'cic_date' => $new_info->cic_date,
                'cic_date_expried' => $new_info->cic_date_expried,
                'cic_number' => $new_info->cic_number,
                'city' => $new_info->city,
                'date_of_birth' => $new_info->date_of_birth,
                'district' => $new_info->district,
                'email' => $new_info->email,
                'phone_number' => $new_info->phone_number,
                'sex' => $new_info->sex,
                'wards' => $new_info->wards,


            ]);
            if ($new_info->photo_url) {
                $path = Storage::putFile('profile-photos', new File($new_info->photo_url));
                $user->profile_photo_path = $path;
                $user->save();
            }

            $user->infor->update([
                'status' => true
            ]);

            return redirect()->route('customer.index')->with('success', "Xét duyệt thông tin khách hàng thành công!");
        } else {
            return redirect()->route('customer.index')->with('warning', "Thông tin cập nhật không có hoặc đã xét duyệt!");
        }
    }
}
