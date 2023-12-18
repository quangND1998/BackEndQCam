<?php

namespace App\Http\Controllers;

use App\Service\Sale\PackageOrderService;
use Illuminate\Http\Request;
use Modules\Order\Repositories\CommissionRepository;
use App\Models\User;
use Illuminate\Support\Carbon;
class CommissionsPackagesController extends Controller
{
    //
    public $commissionRepository,$packageOrderService;
    public function __construct(CommissionRepository $commissionRepository,PackageOrderService $packageOrderService) {
        $this->commissionRepository = $commissionRepository;
        $this->packageOrderService = $packageOrderService;
    }
    public function index(){
        $user = User::find(8);
        $data = $this->commissionRepository->getAllOrderInMonth($this->packageOrderService,$user);

        return $data;
    }
}
