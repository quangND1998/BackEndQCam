<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Order\app\Models\OrderPackage;
use App\Service\Sale\PackageOrderService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashBoardController extends Controller{

    public $packageOrderService;
    public function __construct(PackageOrderService $packageOrderService  ) {
        $this->packageOrderService = $packageOrderService;
    }
    public function index(Request $request){
        //allserver
        $user= Auth::user();

        $top_ten_sale_data = $this->packageOrderService->getTopTenSale('week');
      
        $week_data_user = $this->packageOrderService->sumbyTimeUser('week', $user);
      
        $month_data_user = $this->packageOrderService->sumbyTimeUser('month',$user );
        $year_data_user = $this->packageOrderService->sumbyTimeUser('year',$user );

        if($user->team){
            $team_sale_data = $this->packageOrderService->getTopTenSaleTeam('week',$user->team);
        }
        else{
            $team_sale_data =[];
        }

        $contract_infor = $this->packageOrderService->contractInfor($user);
        if(!$contract_infor){
            $contract_infor =null;
        }
        $ranking_team =[
            'week' =>  $this->packageOrderService->rankingTeam('week', $user, $user->team),
            'month' =>  $this->packageOrderService->rankingTeam('month', $user, $user->team),
            'year' =>  $this->packageOrderService->rankingTeam('year', $user, $user->team),
        ];

        $ranking_all_server =[
            'week' =>  $this->packageOrderService->rankingAllServe('week', $user),
            'month' =>  $this->packageOrderService->rankingAllServe('month', $user ),
            'year' =>  $this->packageOrderService->rankingAllServe('year', $user),
        ];
 
        $order_packages = $this->packageOrderService->getOrder($request->only('date','from', 'to', 'day'),$user)->paginate(10)->appends(['page' => $request->page, 'date' => $request->date, 'from' => $request->from, 'to' => $request->to,'day' => $request->day]);;
        $sumGrandTotalOrder = $this->packageOrderService->sumGrandTotalOrder($request->only('date','from', 'to', 'day'),$user);
        $sumPricePercentOrder = $this->packageOrderService->sumPricePercentOrder($request->only('date','from', 'to', 'day'),$user);
        $analysticData = $this->packageOrderService->formatDataAnalytic($request->only('date','from', 'to', 'day'),$user);
      
        return Inertia::render('Dashboard/Sale', compact( "top_ten_sale_data", 'week_data_user', 'month_data_user', 'year_data_user','team_sale_data','contract_infor','ranking_team', 'ranking_all_server','order_packages','sumGrandTotalOrder','sumPricePercentOrder','analysticData'));

    }


    public function leaderSale(Request $request){
        //allserver
        $user= Auth::user();
        
        $top_ten_sale_data = $this->packageOrderService->getTopTenSale('week');
       
        if($user->hasRole('leader-sale')){
            $userIds= User::where('created_byId', $user->id)->pluck('id');
            
            $leaderIds= User::role('leader-sale')->pluck('id');
        
            $team_sale_data = $this->packageOrderService->getTopTenSaleTeam('month',$user);
        
            $week_data_user = $this->packageOrderService->sumbyTimeTeam('week', $userIds);
            $month_data_user = $this->packageOrderService->sumbyTimeTeam('month',$userIds );
            $year_data_user = $this->packageOrderService->sumbyTimeTeam('year',$userIds );
    
            $contract_infor = $this->packageOrderService->contractInforTeam($user);
            $data_contract=[
                'ref_order_packages_count' => $contract_infor->sum('ref_order_packages_count'),
                'contract_completed' => $contract_infor->sum('contract_completed'),
                'contract_partiallyPaid' => $contract_infor->sum('contract_partiallyPaid'),
                'contract_paid' => $contract_infor->sum('contract_paid'),
                'contract_decline' => $contract_infor->sum('contract_decline'),
                'ref_order_packages_sum_price_percent' => $contract_infor->sum('ref_order_packages_sum_price_percent'),
                'ref_order_packages_sum_grand_total' => $contract_infor->sum('ref_order_packages_sum_grand_total')
            ];
      
            $ranking_all_server =[
                'week' => $this->packageOrderService->getRankingTeamServe('week', $leaderIds, $userIds),
                'month' => $this->packageOrderService->getRankingTeamServe('month', $leaderIds, $userIds),
                'year' => $this->packageOrderService->getRankingTeamServe('year', $leaderIds, $userIds),
            ];

            $order_packages = $this->packageOrderService->getOrderTeam($request->only('date','from', 'to', 'day'),$userIds)->paginate(10)->appends(['page' => $request->page, 'date' => $request->date, 'from' => $request->from, 'to' => $request->to,'day' => $request->day]);;
            $sumGrandTotalOrder = $this->packageOrderService->sumGrandTotalOrdeTeam($request->only('date','from', 'to', 'day'),$userIds);
            $sumPricePercentOrder = $this->packageOrderService->sumPricePercentOrderTeam($request->only('date','from', 'to', 'day'),$userIds);
            $analysticData =  $this->packageOrderService->formatDataAnalyticTeam($request->only('date','from', 'to', 'day'),$userIds);
         
        }   
        // return $order_packages;
        return Inertia::render('Dashboard/LeaderSale', compact( "top_ten_sale_data", 'week_data_user', 'month_data_user', 'year_data_user','team_sale_data','data_contract','ranking_all_server','order_packages','sumGrandTotalOrder','sumPricePercentOrder','analysticData'));

    }
}
