<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\OrderPackage;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Modules\CustomerService\app\Models\DistributeDate;
use App\Models\User;
use Modules\CustomerService\app\Models\DistributeCall;
class GiftDistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderPackages = $this->getOrderPackage($request);
        //  return $orderPackages;

        $this->distributeDate($orderPackages);

        return Inertia::render('Modules/CSKH/Distribute/gift_distribution', compact('orderPackages'));
    }
    public function getRolePackage(Request $request){
        $orderPackages = $this->getOrderPackage($request);
        // return $orderPackages;
        return Inertia::render('Modules/CSKH/Distribute/Role', compact('orderPackages'));
    }

    public function groupByOrderStatus()
    {
        // chua nhan qua l2
        // qua han 15 ngay chua nhan qua

    }
    public function getOrderPackage($request){
        $results = OrderPackage::with(['customer','product_service','distributeDate','product_service_owner.product'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')->orwhere('phone_number','LIKE','%' . $request->search . '%');
            }
        )
        ->where('status','complete')
        ->orderBy('user_id')->orderBy('created_at', 'desc')
        ->fillter($request->only('search'));

        $orderPackages = $results->paginate($request->per_page ? $request->per_page : 5);
        return $orderPackages;
        // return $results;
    }

    public function distributeDate($orderPackages){
        // toi da tao 12 lan, Ngày nhận quà = ngày kích hoạt  + 25 ngày. trùng ngày chủ nhật chuyển trước 1 ngày
        foreach($orderPackages as $order){
            $dayDistant = 25;
            if(count($order->distributeDate) == 0){
                for($i=0; $i<$order->product_service->number_receive_product; $i++){
                    $date = Carbon::parse($order->time_approve)->addDays($dayDistant);
                    $datecall = Carbon::parse($date)->subDays(2);

                    if($date->isSunday()){
                        $date = $date->addDays(1);
                    }
                    $distributeDate = new DistributeDate;
                    $distributeDate->date_recevie = $date;
                    $distributeDate->order_package_id = $order->id;
                    $distributeDate->save();


                    // distributeCall = ngây kich hoat + 23
                    if($datecall->isSunday()){
                        $datecall = $datecall->addDays(1);
                    }
                    $distributeCall = new DistributeCall;
                    $distributeCall->date_call = $datecall;
                    $distributeCall->order_package_id = $order->id;
                    $distributeCall->save();

                    $dayDistant += 25;
                }
            }
        }
    }
}
