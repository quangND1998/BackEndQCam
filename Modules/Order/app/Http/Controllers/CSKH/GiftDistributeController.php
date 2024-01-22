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

class GiftDistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $results = OrderPackage::with(['customer','distributeDate', 'product_service','historyPayment.order_package_payment','historyPayment.user','saler','product_service_owner','history_extend.contract.lastcontract.images'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')->orwhere('phone_number','%' . $request->search . '%');
            }
        )

        ->orwhere('order_number', 'LIKE', '%' . $request->search . '%')
        ->orwhere('idPackage', 'LIKE', '%' . $request->search . '%')
        ->role()
        ->where('status','complete')
        ->fillter($request->only('from', 'to', 'payment_status', 'payment_method', 'type','document_status'));
        $orderPackages = $results->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 5);

        $this->distributeDate($orderPackages);
        // return $orderPackages;
        return Inertia::render('Modules/CSKH/gift_distribution', compact('orderPackages'));
    }

    public function groupByOrderStatus()
    {
        // chua nhan qua l2
        // qua han 15 ngay chua nhan qua

    }
    public function distributeDate($orderPackages){
        // toi da tao 12 lan, Ngày nhận quà = ngày kích hoạt  + 25 ngày. trùng ngày chủ nhật chuyển trước 1 ngày
        $dayDistant = 25;
        foreach($orderPackages as $order){

            if(count($order->distributeDate) == 0){

                for($i=0; $i<12; $i++){
                    $date = Carbon::parse($order->time_approve)->addDays($dayDistant);
                    if($date->isWeekend()){
                        $date = $date->subDays(1);
                    }
                    $distributeDate = new DistributeDate;
                    $distributeDate->date_recevie = $date;
                    $distributeDate->order_package_id = $order->id;
                    $distributeDate->save();
                    $dayDistant += 25;
                }
            }
        }
    }
}
