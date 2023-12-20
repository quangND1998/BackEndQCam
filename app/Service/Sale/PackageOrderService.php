<?php

namespace App\Service\Sale;

use App\Http\Resources\AnalyticTeamDataResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\OrderPackage;
use Carbon\CarbonPeriod;

class PackageOrderService
{

  public $model;
  public function __construct()
  {
    $this->model = new OrderPackage();
  }
  public function sum()
  {
    return $this->model->sum('price_percent');
  }

  public function sumbyDay($time)
  {
    return $this->model->whereBetween('created_at', [Carbon::now()->subDay($time), Carbon::now()])->sum('price_percent');
  }
  // Lấy danh sách tổng doanh thu toàn hệ thống theo tuần , tháng,  năm
  public function sumbyTime($filters)
  {
    return  $this->model->withSum(
      ['historyPayment' => function ($q) use ($filters) {
        $q->where('status', 'complete');
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
      'amount_received'
    );
  }
  // Lấy danh sách doanh thu toàn hệ thống theo tuần , tháng năm
  public function getSaleData($filters)
  {
    return  User::select('id', 'name', 'created_byId')->whereHas('ref_order_packages.product_service_owner')->withSum(
      ['historyPayments' => function ($q) use ($filters) {

        $q->where('history_payments.status', 'complete');
        if ($filters == 'week') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters == 'year') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } else {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
      }],
      'amount_received'
    )->withCount(['ref_order_packages' => function ($query) use ($filters) {
      if ($filters == 'week') {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      } elseif ($filters == 'month') {
        $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
      } elseif ($filters == 'year') {
        $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
      } else {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
    }])->orderBy('history_payments_sum_amount_received', 'desc')->get();
  }
  // Lấy danh sách doanh thu top 10 theo tuần , tháng năm
  public function getTopTenSale($filters)
  {
    return $this->getSaleData($filters)->take(10);
  }


  // Lấy danh tổng doanh thu của user theo tuần , tháng năm
  public function sumbyTimeUser($time, $user)
  {
    $query = $this->sumbyTime($time)->where('ref_id', $user->id)->get();
    return $query->sum('history_payment_sum_amount_received');
  }


  // Lấy danh sách sale theo team theo tuần , tháng năm
  public function getSaleTeam($filters, $team)
  {

    return  User::select('id', 'name', 'created_byId')->where('created_byId', $team->id)->whereHas('ref_order_packages.product_service_owner')->withSum(
      ['historyPayments' => function ($q) use ($filters) {

        $q->where('history_payments.status', 'complete');
        if ($filters == 'week') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters == 'year') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } else {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
      }],
      'amount_received'
    )->withCount(['ref_order_packages' => function ($query) use ($filters) {
      if ($filters == 'week') {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      } elseif ($filters == 'month') {
        $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
      } elseif ($filters == 'year') {
        $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
      } else {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
    }])->orderBy('history_payments_sum_amount_received', 'desc')->get();
  }


  // Lấy danh sách sale top 10 trong team theo tuần , tháng năm
  public function getTopTenSaleTeam($filters, $team)
  {
    return $this->getSaleTeam($filters, $team)->take(10);
  }


  public function rankingAllServe($time, $user)
  {
    $sum = $this->sumbyTimeUser($time, $user);

    return $this->getSaleData($time)->where('history_payments_sum_amount_received', '>=', $sum)->count();
  }

  public function rankingTeam($time, $user, $team)
  {
    $sum = $this->sumbyTimeUser($time, $user);
    return $this->getSaleData($time)->where('history_payments_sum_amount_received', '>=', $sum)->count();
  }


  public function contractInfor($user)
  {
    return  User::select('id', 'name')->whereHas('ref_order_packages')->withCount([
      'ref_order_packages', 'ref_order_packages AS contract_completed' => function ($query) {
        $query->where('status', 'complete');
      }, 'ref_order_packages AS contract_partiallyPaid' => function ($query) {
        $query->whereColumn('price_percent', '<', 'grand_total');
      },
      'ref_order_packages AS contract_paid' => function ($query) {
        $query->whereColumn('price_percent', '=', 'grand_total');
      },
      'ref_order_packages AS contract_decline' => function ($query) {
        $query->where('status', 'decline');
      },
    ])->withSum(
      'ref_order_packages',
      'price_percent'
    )->withSum(
      'ref_order_packages',
      'grand_total'
    )->find($user->id);
  }

  public function getOrder($filters, $user)
  {
    return $this->model->with(['customer','historyPayment' =>function($q)use($filters){
      $q->filterTime($filters);

    },'commissions_packages'=>function($q) use($filters,$user){
      $q->filterTime($filters);
      $q->where('user_id',$user->id);
    }])->whereHas(
      'customer'
    )->whereHas('historyPayment', function ($q) use ($filters,$user) {
        $q->filterTime($filters);
    })->withSum(
      'historyPayment',
      'amount_received'
    )->withSum(
      'commissions_packages',
      'commission_amount'
    )->withSum(
      'commissions_packages',
      'commission_paid'
    )->withSum(
      'commissions_packages',
      'commission_unpaid'
    )->orderBy('created_at', 'desc')->where('ref_id', $user->id);
  }
  public function getOrderAll($filters)
  {
    return $this->model->with(['customer','commissions_packages'])->whereHas(
      'customer'
    )->whereHas('historyPayment', function ($q) use ($filters) {

      $q->filterTime($filters);
    })->withSum(
      ['historyPayment' => function ($q) use ($filters) {
        $q->filterTime($filters);
      }],
      'amount_received'
    )->orderBy('created_at', 'desc');
  }
  public function getOrderInMonth($user){
    return $this->sumbyTime('month')->where('ref_id', $user->id)
    ->orwhere('to_id',$user->id)->orwhere('customer_resources_id',$user->id)
    ->get();
  }
  public function getOrderNotDecline($filters, $user)
  {
    return $this->getOrder($filters, $user)->where('status', '!=', 'decline');
  }

  public function sumGrandTotalOrder($filters, $user)
  {
    return $this->getOrder($filters, $user)->where('status', '!=', 'decline')->sum('grand_total');
  }
  public function sumPricePercentOrder($filters, $user)
  {
    return $this->getOrder($filters, $user)->where('status', '!=', 'decline')->sum('price_percent');
  }

  public function sumCommission($filters, $user)
  {
    return $this->getOrder($filters, $user)->get();
  }
  // public function sumCommissionPaid($filters, $user)
  // {
  //   return $this->getOrder($filters, $user)->get()->sum('commissions_packages_sum_commission_paid');
  // }
  // public function sumCommissionUnPaid($filters, $user)
  // {
  //   return $this->getOrder($filters, $user)->get()->sum('commissions_packages_sum_commission_unpaid');
  // }


  public function analysticData($filters, $user,  $groupBy)
  {
    // $query = $this->model::with(['historyPayment' => function ($q) use ($filters) {
    //   $q->filterTime($filters);
    // }])->select(
    //   DB::raw("CAST((SUM(grand_total))  AS INTEGER) as  grand_total_sum"),
    //   DB::raw("CAST((SUM(price_percent))  AS INTEGER) as  price_percent_sum"),
    //   DB::raw('DATE_FORMAT(created_at, "%m") as month'),
    //   DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as time'),
    // )->where('ref_id', $user->id);
    // return $query->get();
    // return $this->formatDataAnalytic($filters, $query);

    return User::select('id', 'name')->with(['historyPayments' => function ($q) use ($filters,$groupBy) {
        $q->where('history_payments.status', 'complete');
        if (isset($filters['date'])) {

          if ($filters['date'] == 'week') {
            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
          } elseif ($filters['date'] == 'beforMonth') {

            $q->whereBetween('history_payments.updated_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
          } elseif ($filters['date'] == 'month') {


            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
          } elseif ($filters['date'] == 'year') {

            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
          } else {

            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
          }
        }

        if (isset($filters['day'])) {

          $q->whereBetween('history_payments.updated_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['from']) && isset($filters['to'])) {
            $to= Carbon::parse($filters['to'])->format('Y-m-d H:i:s');
            $from= Carbon::parse($filters['from'])->format('Y-m-d H:i:s');
            $q->whereBetween('history_payments.updated_at', [ Carbon::createFromFormat('Y-m-d H:i:s', $from, 'UTC')->setTimezone('+7'), Carbon::createFromFormat('Y-m-d H:i:s', $to, 'UTC')->setTimezone('+7')]);
        }
        $q->select(
          'history_payments.id', 'history_payments.order_package_id',
          DB::raw("CAST((SUM(amount_received))  AS INTEGER) as  amount_received_sum"),
          DB::raw('DATE_FORMAT(history_payments.updated_at, "%m") as month'),
          DB::raw('DATE_FORMAT(history_payments.updated_at, "%Y-%m-%d") as time'),
        )->groupBy('ref_id')->groupBy($groupBy);
      },
    ]

    )->with(['ref_order_packages' => function ($q) use ($filters, $groupBy) {
      $q->filtertime($filters);
      $q->select(
        'id',
        'ref_id',
        DB::raw("CAST((SUM(grand_total))  AS INTEGER) as  grand_total_sum"),
        DB::raw("CAST((SUM(price_percent))  AS INTEGER) as  price_percent_sum"),
        DB::raw('DATE_FORMAT(created_at, "%m") as month'),
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as time'),
      )->groupBy('ref_id')->groupBy($groupBy);
    }])->find($user->id);

  }

  public function formatDataAnalytic($filters, $user)
  {

    if (isset($filters) && count($filters) == 0) {

      $statMonth = Carbon::now()->firstOfMonth();
      $endOfMonth = Carbon::now()->endOfMonth();
      $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
      $newCollections = $this->analysticData($filters, $user, 'time');
      return $this->addDataCollection($ranges, $newCollections, $type = 'time');
    }
    if (isset($filters['date'])) {

      if ($filters['date'] == 'beforMonth') {
        $statMonth = Carbon::now()->subMonth(1)->firstOfMonth();
        $endOfMonth = Carbon::now()->subMonth(1)->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $this->analysticData($filters, $user, 'time');

        return $this->addDataCollection($ranges, $newCollections, 'time');
      } elseif ($filters['date'] == 'month') {
        $statMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $this->analysticData($filters, $user, 'time');

        return $this->addDataCollection($ranges, $newCollections, 'time');
      } elseif ($filters['date'] == 'year') {

        $statOfYear = Carbon::now()->startOfYear();
        $endOfyear = Carbon::now()->endOfYear();
        $ranges = CarbonPeriod::create($statOfYear, '1 Month', $endOfyear);
        $newCollections = $this->analysticData($filters, $user, 'month');

        return $this->addDataCollection($ranges, $newCollections, 'month');
      } else {

        $statMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $this->analysticData($filters, $user, 'time');
        return $this->addDataCollection($ranges, $newCollections, 'time');
      }
    }

    if (isset($filters['day'])) {
      $statOfDay = Carbon::now()->subDay($filters['day']);
      $now = Carbon::now();
      $ranges = CarbonPeriod::create($statOfDay, '1 day', $now);
      $newCollections = $this->analysticData($filters, $user, 'time');

      return $this->addDataCollection($ranges, $newCollections, 'time');
    }
    if (isset($filters['from']) && isset($filters['to'])) {
    
      $ranges = CarbonPeriod::create(Carbon::parse($filters['from'])->format('Y-m-d H:i:s'), '1 day', Carbon::parse($filters['to'])->format('Y-m-d H:i:s'));
      $newCollections = $this->analysticData($filters, $user, 'time');

      return $this->addDataCollection($ranges, $newCollections, 'time');
    }
  }

  public function addDataCollection($ranges, $collection, $type)
  {
   
    $newCollections = [];
    foreach ($ranges as $date) {
      if ($type == 'month') {
        $filtered = $collection->historyPayments->where('month', date("m", strtotime($date)))->first();
      } else {
        $filtered = $collection->historyPayments->where('time', date("Y-m-d", strtotime($date)))->first();
      }
      if (!$filtered) {
        $newCollections[] = array(
          'grand_total_sum' => 0,
          'price_percent_sum' => 0,
          'history_payment_sum_amount_received' => 0,
          'time' => Carbon::parse($date)->format('Y-m-d'),
          'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
        );
      } else {

        $order_package=$collection->ref_order_packages->find($filtered->order_package_id);
        if(!$order_package){
          $order=$this->model->find($filtered->order_package_id);
        }
        $newCollections[] = array(
          'grand_total_sum' => $order_package ? $order_package->grand_total_sum: $order->grand_total,
          'price_percent_sum' => $order_package ? $order_package->grand_total_sum: $order->price_percent,
          'history_payment_sum_amount_received' =>  $filtered->amount_received_sum,
          'time' => Carbon::parse($date)->format('Y-m-d'),
          'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
        );
      }
    }
    if ($type == 'time') {
      $sorted = collect($newCollections)->sortBy('time');
    }
    if ($type == 'month') {
      $sorted = collect($newCollections)->sortBy('time');
    }

    $server_minutes =  $sorted->values();
    return $server_minutes;
  }

  public function sumbyTimeTeam($time, $userIds)
  {
    $query = $this->sumbyTime($time)->whereIn('ref_id', $userIds)->get();
    return $query->sum('history_payment_sum_amount_received');
  }

  public function contractInforTeam($user)
  {
    return  User::select('id', 'name')->whereHas('ref_order_packages')->withCount([
      'ref_order_packages', 'ref_order_packages AS contract_completed' => function ($query) {
        $query->where('status', 'complete');
      }, 'ref_order_packages AS contract_partiallyPaid' => function ($query) {
        $query->whereColumn('price_percent', '<', 'grand_total');
      },
      'ref_order_packages AS contract_paid' => function ($query) {
        $query->whereColumn('price_percent', '=', 'grand_total');
      },
      'ref_order_packages AS contract_decline' => function ($query) {
        $query->where('status', 'decline');
      },
    ])->withSum(
      'ref_order_packages',
      'price_percent'
    )->withSum(
      'ref_order_packages',
      'grand_total'
    )->where('created_byId', $user->id)->get();
  }

  public function getSaleDataTeam($filters, $leaderIds)
  {

    $data =  User::whereIn('created_byId', $leaderIds)->withSum(
      ['historyPayments' => function ($q) use ($filters) {

        $q->where('history_payments.status', 'complete');
        if ($filters == 'week') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters == 'year') {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } else {
          $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
      }],
      'amount_received'
    )->get();
    $newCollection = $data->groupBy('created_byId');
    $groupwithcount = $newCollection->map(function ($group) {
      return [
        'team_id' => $group->first()['created_byId'], // opposition_id is constant inside the same group, so just take the first or whatever.
        'sum' => $group->sum('history_payments_sum_amount_received'),
      ];
    })->sortByDesc('sum');

    foreach ($leaderIds as $leader) {
      if (!$groupwithcount->has($leader)) {
        $groupwithcount->put($leader, ['team_id' => $leader, 'sum' => 0]);
      }
    }
    return $groupwithcount->values();
  }
  public function getRankingTeamServe($filters, $leaderIds, $userIds)
  {
    $sum = $this->sumbyTimeTeam($filters, $userIds);
    $data = $this->getSaleDataTeam($filters, $leaderIds);
    return $data->where('sum', '>=', $sum)->count();
  }

  public function getOrderTeam($filters, $userIds)
  {
    return $this->model->with(['customer','historyPayment' =>function($q)use($filters){
      $q->filterTime($filters);

    },'commissions_packages'=>function($q) use($filters){
      $q->filterTime($filters);

    }])->whereHas('historyPayment', function ($q) use ($filters) {

      $q->filterTime($filters);
    })->withSum(
    'historyPayment',
    'amount_received'
  )->withSum(
    'commissions_packages',
    'commission_amount'
  )->withSum(
    'commissions_packages',
    'commission_paid'
  )->withSum(
    'commissions_packages',
    'commission_unpaid'
  )->orderBy('created_at', 'desc')->whereIn('ref_id', $userIds);
  }


  public function sumGrandTotalOrdeTeam($filters, $userIds)
  {
    return $this->getOrderTeam($filters, $userIds)->where('status', '!=', 'decline')->sum('grand_total');
  }

  public function sumPricePercentOrderTeam($filters, $userIds)
  {
    return $this->getOrderTeam($filters, $userIds)->where('status', '!=', 'decline')->sum('price_percent');
  }
  public function getOrderTeamNotDecline($filters, $userIds)
  {
    return $this->getOrderTeam($filters, $userIds)->where('status', '!=', 'decline');
  }

  public function getUserData($filters, $userIds, $groupBy)
  {
    return User::select('id', 'name')
      ->with(['historyPayments' => function ($q) use ($filters, $groupBy) {
        $q->where('history_payments.status', 'complete');
        if (isset($filters['date'])) {

          if ($filters['date'] == 'week') {
            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
          } elseif ($filters['date'] == 'beforMonth') {

            $q->whereBetween('history_payments.updated_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
          } elseif ($filters['date'] == 'month') {


            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
          } elseif ($filters['date'] == 'year') {

            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
          } else {

            $q->whereBetween('history_payments.updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
          }
        }

        if (isset($filters['day'])) {

          $q->whereBetween('history_payments.updated_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['from']) && isset($filters['to'])) {

          $to= Carbon::parse($filters['to'])->format('Y-m-d H:i:s');
          $from= Carbon::parse($filters['from'])->format('Y-m-d H:i:s');
          $q->whereBetween('history_payments.created_at', [ Carbon::createFromFormat('Y-m-d H:i:s', $from, 'UTC')->setTimezone('+7'), Carbon::createFromFormat('Y-m-d H:i:s', $to, 'UTC')->setTimezone('+7')]);
        }
        $q->select(
          'history_payments.id',
          DB::raw("CAST((SUM(amount_received))  AS INTEGER) as  amount_received_sum"),
          DB::raw('DATE_FORMAT(history_payments.updated_at, "%m") as month'),
          DB::raw('DATE_FORMAT(history_payments.updated_at, "%Y-%m-%d") as time'),
        )->groupBy('ref_id')->groupBy($groupBy);
      }])->whereIn('id', $userIds)->get();
  }
  public function formatDataAnalyticTeam($filters, $userIds)
  {

    if (isset($filters) && count($filters) == 0) {

      $statMonth = Carbon::now()->firstOfMonth();
      $endOfMonth = Carbon::now()->endOfMonth();
      $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
      $newCollections = $this->getUserData($filters, $userIds, 'time');
      return $this->addDataCollectionTeam($ranges, $newCollections, $type = 'time');
    }
    if (isset($filters['date'])) {

      if ($filters['date'] == 'beforMonth') {
        $statMonth = Carbon::now()->subMonth(1)->firstOfMonth();
        $endOfMonth = Carbon::now()->subMonth(1)->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $this->getUserData($filters, $userIds, 'time');

        return $this->addDataCollectionTeam($ranges, $newCollections, 'time');
      } elseif ($filters['date'] == 'month') {
        $statMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $this->getUserData($filters, $userIds, 'time');


        return $this->addDataCollectionTeam($ranges, $newCollections, 'time');
      } elseif ($filters['date'] == 'year') {

        $statOfYear = Carbon::now()->startOfYear();
        $endOfyear = Carbon::now()->endOfYear();
        $ranges = CarbonPeriod::create($statOfYear, '1 Month', $endOfyear);
        $newCollections = $this->getUserData($filters, $userIds, 'month');

        return $this->addDataCollectionTeam($ranges, $newCollections, 'month');
      } else {

        $statMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $this->getUserData($filters, $userIds, 'time');
        return $this->addDataCollectionTeam($ranges, $newCollections, 'time');
      }
    }

    if (isset($filters['day'])) {
      $statOfDay = Carbon::now()->subDay($filters['day']);
      $now = Carbon::now();
      $ranges = CarbonPeriod::create($statOfDay, '1 day', $now);
      $newCollections = $this->getUserData($filters, $userIds, 'time');

      return $this->addDataCollectionTeam($ranges, $newCollections, 'time');
    }
    if (isset($filters['from']) && isset($filters['to'])) {
      $ranges = CarbonPeriod::create(Carbon::parse($filters['from'])->format('Y-m-d H:i:s'), '1 day', Carbon::parse($filters['to'])->format('Y-m-d H:i:s'));
      $newCollections = $this->getUserData($filters, $userIds, 'time');
      return $this->addDataCollectionTeam($ranges, $newCollections, 'time');
    }
  }

  public function addDataCollectionTeam($ranges, $collection, $type)
  {
    $newCollection = [];

    foreach ($ranges as $date) {
      foreach ($collection as $item) {
        $newCollection[] = array(
          'id' => $item->id,
          'name' => $item->name,
          'history_payments' => []
        );

        if (count($item->historyPayments) == 0) {

          // $item->ref_order_packages[] = array(
          //   'ref_id' => $item->id,
          //   'price_percent_sum' => 0,
          //   'grand_total_sum' => 0,
          //   'time' => Carbon::parse($date)->format('Y-m-d'),
          //   'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
          // );

          $keys = array_column($newCollection, 'id');

          $index = array_search($item->id, $keys);

          if ($index !== false) {
            $newCollection[$index]['history_payments'][] = array(
              'ref_id' => $item->id,
              'amount_received_sum' => 0,
              'time' => Carbon::parse($date)->format('Y-m-d'),
              'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
            );
          }
        } else {

          if ($type == 'month') {
            $filtered = $item->historyPayments->where('month', date("m", strtotime($date)));
          } else {
            $filtered = $item->historyPayments->where('time', date("Y-m-d", strtotime($date)));
          }
          if (count($filtered) == 0) {

            $keys = array_column($newCollection, 'id');

            $index = array_search($item->id, $keys);

            if ($index !== false) {
              $newCollection[$index]['history_payments'][] = array(
                'ref_id' => $item->id,
                'amount_received_sum' => 0,
                'time' => Carbon::parse($date)->format('Y-m-d'),
                'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
              );
            }
          } else {

            $keys = array_column($newCollection, 'id');

            $index = array_search($item->id, $keys);

            if ($index !== false) {
              $newCollection[$index]['history_payments'][] = array(
                'ref_id' => $item->id,
                'amount_received_sum' =>  $filtered->first()['amount_received_sum'],
                'time' => Carbon::parse($date)->format('Y-m-d'),
                'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
              );
            }
          }
        }
      }
    }
    $data = array_filter($newCollection, function ($item) {
      return count($item["history_payments"]) > 0;
    });
    return $data;
  }
}
