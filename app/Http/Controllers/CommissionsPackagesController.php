<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Repositories\CommissionRepository;
class CommissionsPackagesController extends Controller
{
    //
    public $commissionRepository;
    public function __construct(CommissionRepository $commissionRepository  ) {
        $this->commissionRepository = $commissionRepository;
    }
    public function index(){
        $data = $this->commissionRepository->getAllOrderInMonth();
        return $data;
    }
}
