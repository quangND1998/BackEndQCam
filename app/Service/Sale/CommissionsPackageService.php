<?php

namespace App\Service\Sale;

use App\Http\Resources\AnalyticTeamDataResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\OrderPackage;
use Carbon\CarbonPeriod;
use Modules\Order\app\Models\commissionsPackage;

class CommissionsPackageService
{

  public $model;
  public function __construct()
  {
    $this->model = new commissionsPackage();
  }


  public function sumbyTime($filters){
    return  User::withSum(
        ['commission' => function ($q) use ($filters) {
          if ($filters == 'week') {
            $q->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
          } elseif ($filters == 'month') {
            $q->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
          } elseif ($filters == 'year') {
            $q->whereBetween('updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
          } else {
            $q->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
          }
        }],
        'commission_amount'
      );
  }

  public function sumbyTimeUser($time, $user)
  {
    $query = $this->sumbyTime($time)->find($user->id);
    return $query->commission_sum_commission_amount;
  }

  public function sumbyTimeTeam($time, $userIds)
  {
    $query = $this->sumbyTime($time)->whereIn('id',$userIds)->get();
    return $query->sum('commission_sum_commission_amount');
  }

}