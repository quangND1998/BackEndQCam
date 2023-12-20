<?php

namespace App\Http\Controllers;

use App\Exports\SaleDataExport;
use App\Models\User;
use App\Service\Sale\CommissionsPackageService;
use Illuminate\Http\Request;
use Modules\Order\app\Models\OrderPackage;
use App\Service\Sale\PackageOrderService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class DashBoardController extends Controller
{

    public $packageOrderService, $commissionsPackageService;
    public function __construct(PackageOrderService $packageOrderService, CommissionsPackageService $commissionsPackageService)
    {
        $this->packageOrderService = $packageOrderService;
        $this->commissionsPackageService = $commissionsPackageService;
    }
    public function index(Request $request)
    {
        //allserver
        $user = Auth::user();

        $top_ten_sale_data = $this->packageOrderService->getTopTenSale('week');

        $week_data_user = $this->packageOrderService->sumbyTimeUser('week', $user);

        $month_data_user = $this->packageOrderService->sumbyTimeUser('month', $user);
        $year_data_user = $this->packageOrderService->sumbyTimeUser('year', $user);

        $week_commission = $this->commissionsPackageService->sumbyTimeUser('week', $user);
        $month_commission = $this->commissionsPackageService->sumbyTimeUser('month', $user);
        $year_commission = $this->commissionsPackageService->sumbyTimeUser('year', $user);


        if ($user->team) {
            $team_sale_data = $this->packageOrderService->getTopTenSaleTeam('week', $user->team);
        } else {
            $team_sale_data = [];
        }

        $contract_infor = $this->packageOrderService->contractInfor($user);
        if (!$contract_infor) {
            $contract_infor = null;
        }
        $ranking_team = [
            'week' =>  $this->packageOrderService->rankingTeam('week', $user, $user->team),
            'month' =>  $this->packageOrderService->rankingTeam('month', $user, $user->team),
            'year' =>  $this->packageOrderService->rankingTeam('year', $user, $user->team),
        ];

        $ranking_all_server = [
            'week' =>  $this->packageOrderService->rankingAllServe('week', $user),
            'month' =>  $this->packageOrderService->rankingAllServe('month', $user),
            'year' =>  $this->packageOrderService->rankingAllServe('year', $user),
        ];

        $order_packages = $this->packageOrderService->getOrder($request->only('date', 'from', 'to', 'day'), $user)->paginate(10)->appends(['page' => $request->page, 'date' => $request->date, 'from' => $request->from, 'to' => $request->to, 'day' => $request->day]);;
        $orderNotDecline = $this->packageOrderService->getOrderNotDecline($request->only('date', 'from', 'to', 'day'), $user);
        $sumGrandTotalOrder = $orderNotDecline->sum('grand_total');
        $sumPricePercentOrder = $orderNotDecline->sum('price_percent');

        $sumCommission = $this->packageOrderService->sumCommission($request->only('date', 'from', 'to', 'day'), $user);
        $sumCommissionInfo = [
            'sum_commision' => $sumCommission->sum('commissions_packages_sum_commission_amount'),
            'sum_commision_paid' => $sumCommission->sum('commissions_packages_sum_commission_paid'),
            'sum_commision_unpaid' => $sumCommission->sum('commissions_packages_sum_commission_unpaid'),
        ];


        // $sumCommissionPaid = $this->packageOrderService->sumCommissionPaid($request->only('date','from', 'to', 'day'),$user);
        // $sumCommissionUnPaid = $this->packageOrderService->sumCommissionUnPaid($request->only('date','from', 'to', 'day'),$user);

        $analysticData = $this->packageOrderService->formatDataAnalytic($request->only('date', 'from', 'to', 'day'), $user);

        // return Inertia::render('Dashboard/Sale', compact( "top_ten_sale_data", 'week_data_user', 'month_data_user', 'year_data_user','team_sale_data','contract_infor','ranking_team', 'ranking_all_server','order_packages'));
        return Inertia::render('Dashboard/Sale', compact(
            "top_ten_sale_data",
            'week_data_user',
            'month_data_user',
            'year_data_user',
            'team_sale_data',
            'contract_infor',
            'ranking_team',
            'ranking_all_server',
            'order_packages',
            'sumGrandTotalOrder',
            'sumPricePercentOrder',
            'analysticData',
            'week_commission',
            'month_commission',
            'year_commission',
            'sumCommissionInfo'
        ));
    }


    public function leaderSale(Request $request)
    {
        //allserver
        $user = Auth::user();

        $top_ten_sale_data = $this->packageOrderService->getTopTenSale('week');

        if ($user->hasRole('leader-sale')) {
            $userIds = User::where('created_byId', $user->id)->pluck('id');

            $leaderIds = User::role('leader-sale')->pluck('id');

            $team_sale_data = $this->packageOrderService->getTopTenSaleTeam('month', $user);
            $week_data_user = $this->packageOrderService->sumbyTimeTeam('week', $userIds);
            $month_data_user = $this->packageOrderService->sumbyTimeTeam('month', $userIds);
            $year_data_user = $this->packageOrderService->sumbyTimeTeam('year', $userIds);


            $week_commission = $this->commissionsPackageService->sumbyTimeTeam('week', $userIds);
            $month_commission = $this->commissionsPackageService->sumbyTimeTeam('month', $userIds);
            $year_commission = $this->commissionsPackageService->sumbyTimeTeam('year', $userIds);


            $contract_infor = $this->packageOrderService->contractInforTeam($user);
            $data_contract = [
                'ref_order_packages_count' => $contract_infor->sum('ref_order_packages_count'),
                'contract_completed' => $contract_infor->sum('contract_completed'),
                'contract_partiallyPaid' => $contract_infor->sum('contract_partiallyPaid'),
                'contract_paid' => $contract_infor->sum('contract_paid'),
                'contract_decline' => $contract_infor->sum('contract_decline'),
                'ref_order_packages_sum_price_percent' => $contract_infor->sum('ref_order_packages_sum_price_percent'),
                'ref_order_packages_sum_grand_total' => $contract_infor->sum('ref_order_packages_sum_grand_total')
            ];

            $ranking_all_server = [
                'week' => $this->packageOrderService->getRankingTeamServe('week', $leaderIds, $userIds),
                'month' => $this->packageOrderService->getRankingTeamServe('month', $leaderIds, $userIds),
                'year' => $this->packageOrderService->getRankingTeamServe('year', $leaderIds, $userIds),
            ];

            $order_packages = $this->packageOrderService->getOrderTeam($request->only('date', 'from', 'to', 'day'), $userIds)->paginate(10)->appends(['page' => $request->page, 'date' => $request->date, 'from' => $request->from, 'to' => $request->to, 'day' => $request->day]);;
            $orderTeamNotDecline = $this->packageOrderService->getOrderTeamNotDecline($request->only('date', 'from', 'to', 'day'), $userIds)->get();
            $sumGrandTotalOrder = $orderTeamNotDecline->sum('grand_total');
            $sumPricePercentOrder =  $orderTeamNotDecline->sum('price_percent');
            $sumCommissionInfo = [
                'sum_commision' => $orderTeamNotDecline->sum('commissions_packages_sum_commission_amount'),
                'sum_commision_paid' => $orderTeamNotDecline->sum('commissions_packages_sum_commission_paid'),
                'sum_commision_unpaid' => $orderTeamNotDecline->sum('commissions_packages_sum_commission_unpaid'),
            ];

            $analysticData =  $this->packageOrderService->formatDataAnalyticTeam($request->only('date', 'from', 'to', 'day'), $userIds);
        }
        // return $order_packages;
        return Inertia::render('Dashboard/LeaderSale', compact(
            "top_ten_sale_data",
            'week_data_user',
            'month_data_user',
            'year_data_user',
            'team_sale_data',
            'data_contract',
            'ranking_all_server',
            'order_packages',
            'sumGrandTotalOrder',
            'sumPricePercentOrder',
            'analysticData',
            'sumCommissionInfo',
            'week_commission',
            'month_commission',
            'year_commission'
        ));
    }


    public function view(Request $request)
    {
        // $order_packages = $this->packageOrderService->getOrder($request->only('date','from', 'to', 'day'),Auth::user())->get();
        return view('exports.sale-order', compact('order_packages'));
    }

    public function export(Request $request)
    {


        $order_packages = $this->packageOrderService->getOrder($request->only('date', 'from', 'to', 'day'), Auth::user())->get();

        if (!$request->date) {
            $name = 'month';
        }
        if ($request->date) {

            if ($request->date == 'week') {
                $name = 'weeck';
            } elseif ($request->date == 'beforMonth') {
                $name = 'beforMonth';
            } elseif ($request->date == 'month') {
                $name = 'month';
            } elseif ($request->date == 'year') {
                $name = 'year';
            } else {
                $name = 'month';
            }
        }

        if ($request->day) {


            $name = $request->day . 'day';
        }

        if ($request->from && $request->to) {

            $name = $request->from . 'dayto' . $request->to;
        }

        return Excel::download(new SaleDataExport($order_packages),  $name . '-sale.xlsx');
    }


    public function exportLeaderSale(Request $request)
    {
        $user= Auth::user();
        if ($user->hasRole('leader-sale')) {
            $userIds = User::where('created_byId', $user->id)->pluck('id');

        
            $order_packages = $this->packageOrderService->getOrderTeam($request->only('date', 'from', 'to', 'day'), $userIds)->get();

            if (!$request->date) {
                $name = 'month';
            }
            if ($request->date) {

                if ($request->date == 'week') {
                    $name = 'weeck';
                } elseif ($request->date == 'beforMonth') {
                    $name = 'beforMonth';
                } elseif ($request->date == 'month') {
                    $name = 'month';
                } elseif ($request->date == 'year') {
                    $name = 'year';
                } else {
                    $name = 'month';
                }
            }

            if ($request->day) {


                $name = $request->day . 'day';
            }

            if ($request->from && $request->to) {

                $name = $request->from . 'dayto' . $request->to;
            }
            return Excel::download(new SaleDataExport($order_packages),  $name . '-leader-sale.xlsx');
        }
    }
}
