<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Order\app\Models\OrderPackage;
use App\Service\Sale\PackageOrderService;
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
        $order_packages = $this->packageOrderService->getOrder($request->only('date','from', 'to', 'day'),$user)->paginate(1)->appends(['page' => $request->page, 'date' => $request->date, 'from' => $request->from, 'to' => $request->to,'day' => $request->day]);;
        $sumGrandTotalOrder = $this->packageOrderService->sumGrandTotalOrder($request->only('date','from', 'to', 'day'),$user);
        $sumPricePercentOrder = $this->packageOrderService->sumPricePercentOrder($request->only('date','from', 'to', 'day'),$user);

        $analysticData = $this->packageOrderService->analysticData($request->only('date','from', 'to', 'day'),$user);
        // return $analysticData;

        // return $order_packages;
        return Inertia::render('HomeView', compact( "top_ten_sale_data", 'week_data_user', 'month_data_user', 'year_data_user','team_sale_data','contract_infor','ranking_team', 'ranking_all_server','order_packages','sumGrandTotalOrder','sumPricePercentOrder'));

    }
}
