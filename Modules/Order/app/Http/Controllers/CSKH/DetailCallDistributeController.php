<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Inertia\Inertia;
use Modules\Order\app\Models\OrderPackage;
use Modules\CustomerService\app\Models\DistributeCall;
use Illuminate\Support\Carbon;
use Modules\Order\Repositories\CSKHRepository;
class DetailCallDistributeController extends Controller
{
    protected $cskhResponsive;
    public function __construct(CSKHRepository $cskhResponsive)
    {
        $this->cskhResponsive = $cskhResponsive;
        $this->middleware('permission:cskh', ['only' => ['index']]);
    }
    public function getScheduleDetail(Request $request){
        if($request->fromDate == null || $request->toDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $toDate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $toDate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $list_cskh = $this->getCSKH($request,$fromDate,$toDate);
        // return $list_cskh;
        $orderPackagesNot = $this->cskhResponsive->getOrderNotCSKH($request)->paginate(100);
        $numberNotCSKH = $this->cskhResponsive->getOrderNotCSKH($request)->count();
        // return $numberNotCSKH;
        $offsetWeek = $this->cskhResponsive->getOffsetWeek($toDate);
        return Inertia::render('Modules/CSKH/Distribute/ScheduleDetail', compact('list_cskh','offsetWeek','orderPackagesNot','numberNotCSKH'));
    }
    public function getCSKH($request,$fromDate,$toDate){
        $cskh = User::with(['distribute_call' => function($q) use ($fromDate,$toDate){
            $q->whereBetween('date_call', [$fromDate, $toDate]);
        },
        'distribute_call.orderPackage.customer','distribute_call.orderPackage.product_service_owner.product'])
        ->whereHas(
            'distribute_call',
            function ($q) use ($fromDate,$toDate) {
                $q->whereBetween('date_call', [$fromDate, $toDate]);
            }
        )
        ->whereHas('roles',function ($query) {
            $query->where('name', 'cskh');
        })->where('isActive',1)->get();
         return $cskh;
    }
}
