<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Order\app\Models\OrderPackage;
use App\Service\Sale\PackageOrderService;
use Illuminate\Support\Facades\Auth;

class DashBoardController extends Controller{

    public $packageOrderService;
    public function __construct(PackageOrderService $packageOrderService  ) {
        $this->packageOrderService = $packageOrderService;
    }
    public function index(Request $request){
        //allserver
        $user= Auth::user();
        
        $week_sum = $this->packageOrderService->sumbyTime('week')->sum('price_percent');
        $month_sum = $this->packageOrderService->sumbyTime('month')->sum('price_percent');
        $year_sum = $this->packageOrderService->sumbyTime('year')->sum('price_percent');
        $sale_data = $this->packageOrderService->getTopTenSale('week');
       


        $week_data_user = $this->packageOrderService->sumbyTimeUser('week', $user);
        $month_data_user = $this->packageOrderService->sumbyTimeUser('month',$user );
        $year_data_user = $this->packageOrderService->sumbyTimeUser('year',$user );
        if($user->team){
            $team_sale_data = $this->packageOrderService->getTopTenSaleTeam('week',$user->team);
        }
        else{
            $team_sale_data =[]; 
        }
        $user =User::find(126);

        return $this->packageOrderService->getSaleData('month', $user);

      
        
    }
}
