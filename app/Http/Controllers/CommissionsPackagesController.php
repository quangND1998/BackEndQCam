<?php

namespace App\Http\Controllers;

use App\Service\Sale\PackageOrderService;
use Illuminate\Http\Request;
use Modules\Order\Repositories\CommissionRepository;
use App\Models\User;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use App\Jobs\UpdateCommissionJob;
class CommissionsPackagesController extends Controller
{
    //
    public $commissionRepository,$packageOrderService;
    public function __construct(CommissionRepository $commissionRepository,PackageOrderService $packageOrderService) {
        $this->commissionRepository = $commissionRepository;
        $this->packageOrderService = $packageOrderService;
    }
    public function fresh(Request $request){
        UpdateCommissionJob::dispatch($this->commissionRepository,$this->packageOrderService);
    }
    public function commissionUser(Request $request){
        $roles = ['saler','leader-sale','ctv','telesale'];
        $users = User::with(['commission'])
        ->whereHas('commission')
        ->withSum('commission','commission_amount')
        ->withSum('commission','amount_received')
        ->withSum('commission','commission_paid')
        ->withCount(['ref_order_packages as count_order_notdecline' => function ($query) {
            $query->where('status','!=','decline');
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }])
        ->withCount(['ref_order_packages as count_order_decline' => function ($query) {
            $query->where('status','==','decline');
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }])
        ->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })
        ->orderBy('commission_sum_commission_amount', 'desc');

        $total_users = $users->get();
        $sumCommissionInfo = [
            'sum_count_order_notdecline' => $total_users->sum('count_order_notdecline'),
            'sum_count_order_decline' => $total_users->sum('count_order_decline'),
            'sum_amount_received' => $total_users->sum('commission_sum_amount_received'),
            'sum_commission_amount' => $total_users->sum('commission_sum_commission_amount'),
            'sum_commision_paid' => $total_users->sum('commission_sum_commission_paid'),
            'sum_commision_unpaid' => $total_users->sum('commission_sum_commission_amount'),
        ];
        $users = $users->paginate(10);
        // return  $sumCommissionInfo;
        return Inertia::render('Commission/User', compact('users','sumCommissionInfo'));
    }
    public function detailCommissionUser(Request $request,User $user){
        $order_packages = $this->packageOrderService->getOrder($request->only('date','from', 'to', 'day'),$user)->paginate(10)->appends(['page' => $request->page, 'date' => $request->date, 'from' => $request->from, 'to' => $request->to,'day' => $request->day]);;

        // return $order_packages;
        return Inertia::render('Commission/PackageDetail', compact('order_packages'));
    }
}
