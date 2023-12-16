<?php

namespace App\Service\Sale;

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
    if ($filters == 'week') {
      $query =   $this->model->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    } elseif ($filters == 'month') {
      $query = $this->model->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
    } elseif ($filters == 'year') {
      $query =  $this->model->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
    } else {
      $query =  $this->model->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }
    return $query;
  }
  // Lấy danh sách doanh thu toàn hệ thống theo tuần , tháng năm 
  public function getSaleData($filters)
  {
    return  User::select('id', 'name', 'created_byId')->with('team')->whereHas('ref_order_packages.product_service_owner')->withSum(
      ['ref_order_packages' => function ($q) use ($filters) {
        if ($filters == 'week') {
          $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
          $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters == 'year') {
          $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } else {
          $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
      }],
      'price_percent'
    )->withCount(['ref_order_packages' => function ($q) use ($filters) {
      if ($filters == 'week') {
        $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      } elseif ($filters == 'month') {
        $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
      } elseif ($filters == 'year') {
        $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
      } else {
        $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
    }])->orderBy('ref_order_packages_sum_price_percent', 'desc')->get();
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
    return $query->sum('price_percent');
  }


  // Lấy danh sách sale theo team theo tuần , tháng năm
  public function getSaleTeam($filters, $team)
  {
    return  User::select('id', 'name', 'created_byId')->where('created_byId', $team->id)->with('team')->whereHas('ref_order_packages.product_service_owner')->withSum(
      ['ref_order_packages' => function ($q) use ($filters) {
        if ($filters == 'week') {
          $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
          $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters == 'year') {
          $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } else {
          $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
      }],
      'price_percent'
    )->withCount(['ref_order_packages' => function ($q) use ($filters) {
      if ($filters == 'week') {
        $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      } elseif ($filters == 'month') {
        $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
      } elseif ($filters == 'year') {
        $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
      } else {
        $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      }
    }])->orderBy('ref_order_packages_sum_price_percent', 'desc')->get();
  }


  // Lấy danh sách sale top 10 trong team theo tuần , tháng năm
  public function getTopTenSaleTeam($filters, $team)
  {
    return $this->getSaleTeam($filters, $team)->take(10);
  }


  public function rankingAllServe($time, $user)
  {
    $sum = $this->sumbyTimeUser($time, $user);
    return $this->getSaleData($time)->where('ref_order_packages_sum_price_percent', '>=', $sum)->count();
  }

  public function rankingTeam($time, $user, $team)
  {
    $sum = $this->sumbyTimeUser($time, $user);
    return $this->getSaleData($time)->where('ref_order_packages_sum_price_percent', '>=', $sum)->count();
  }


  public function contractInfor($user)
  {
    return  User::select('id', 'name')->whereHas('ref_order_packages')->withCount([
      'ref_order_packages', 'ref_order_packages AS contract_completed' => function ($query) {
        $query->where('status', 'completed');
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
    return $this->model->with(['customer'])->whereHas(
      'customer'
    )->filtertime($filters)->orderBy('created_at', 'desc')->where('ref_id', $user->id);
  }


  public function sumGrandTotalOrder($filters, $user)
  {
    return $this->getOrder($filters, $user)->where('status', '!=', 'decline')->sum('grand_total');
  }

  public function sumPricePercentOrder($filters, $user)
  {
    return $this->getOrder($filters, $user)->where('status', '!=', 'decline')->sum('price_percent');
  }


  public function analysticData($filters, $user)
  {

    $query = $this->model::select(

      DB::raw("CAST((SUM(grand_total))  AS INTEGER) as  grand_total_sum"),
      DB::raw("CAST((SUM(price_percent))  AS INTEGER) as  price_percent_sum"),
      DB::raw('DATE_FORMAT(created_at, "%M") as month'),
      DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as time'),
    )->filtertime($filters)
      ->where('ref_id', $user->id);
    return $this->formatDataAnalytic($filters, $query);
  }

  public function formatDataAnalytic($filters, $query)
  {

    if (isset($filters) && count($filters) == 0) {

      $statMonth = Carbon::now()->firstOfMonth();
      $endOfMonth = Carbon::now()->endOfMonth();
      $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
      $newCollections = $query->groupBy('time')->get();
      return $this->addDataCollection($ranges, $newCollections, $type = 'time');
    }
    if (isset($filters['date'])) {

      if ($filters['date'] == 'beforMonth') {
        $statMonth = Carbon::now()->subMonth(1)->firstOfMonth();
        $endOfMonth = Carbon::now()->subMonth(1)->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $query->groupBy('time')->get();

        return $this->addDataCollection($ranges, $newCollections, 'time');
      } elseif ($filters['date'] == 'month') {
        $statMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $query->groupBy('time')->get();

        return $this->addDataCollection($ranges, $newCollections, 'time');
      } elseif ($filters['date'] == 'year') {

        $statOfYear = Carbon::now()->startOfYear();
        $endOfyear = Carbon::now()->endOfYear();
        $ranges = CarbonPeriod::create($statOfYear, '1 Month', $endOfyear);
        $newCollections = $query->groupBy('month')->get();

        return $this->addDataCollection($ranges, $newCollections, 'month');
      } else {

        $statMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $ranges = CarbonPeriod::create($statMonth, $endOfMonth);
        $newCollections = $query->groupBy('time')->get();
        return $this->addDataCollection($ranges, $newCollections, 'time');
      }
    }

    if (isset($filters['day'])) {
      $statOfDay = Carbon::now()->subDay($filters['day']);
      $now = Carbon::now();
      $ranges = CarbonPeriod::create($statOfDay, '1 day', $now);
      $newCollections = $query->groupBy('time')->get();

      return $this->addDataCollection($ranges, $newCollections, 'time');
    }
    if (isset($filters['from']) && isset($filters['to'])) {
      $ranges = CarbonPeriod::create(Carbon::parse($filters['from'])->format('Y-m-d H:i:s'), '1 day', Carbon::parse($filters['to'])->format('Y-m-d H:i:s'));
      $newCollections = $query->groupBy('time')->get();
      return $this->addDataCollection($ranges, $newCollections, 'time');
    }
  }

  public function addDataCollection($ranges, $collection, $type)
  {
    $newCollections = $collection;
    foreach ($ranges as $date) {
      if ($type == 'month') {
        $filtered = $collection->where('month', date("m", strtotime($date)));
      } else {
        $filtered = $collection->where('time', date("Y-m-d", strtotime($date)));
      }

      if (count($filtered) == 0) {
        $newCollections[] = array(
          'grand_total_sum' => 0,
          'price_percent_sum' => 0,
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
    return $query->sum('price_percent');
  }

  public function contractInforTeam($user)
  {
    return  User::select('id', 'name')->whereHas('ref_order_packages')->withCount([
      'ref_order_packages', 'ref_order_packages AS contract_completed' => function ($query) {
        $query->where('status', 'completed');
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
      ['ref_order_packages' => function ($q) use ($filters) {
        $q->select(DB::raw('COALESCE(SUM(price_percent), 0)'));
        if ($filters == 'week') {
          $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
          $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters == 'year') {
          $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        } else {
          $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
      }],
      'price_percent'
    )->get();
    $newCollection = $data->groupBy('created_byId');
    $groupwithcount = $newCollection->map(function ($group) {
      return [
        'team_id' => $group->first()['created_byId'], // opposition_id is constant inside the same group, so just take the first or whatever.
        'sum' => $group->sum('ref_order_packages_sum_price_percent'),
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
    return $this->model->with(['customer'])->whereHas(
      'customer'
    )->filtertime($filters)->orderBy('created_at', 'desc')->whereIn('ref_id', $userIds);
  }


  public function sumGrandTotalOrdeTeam($filters, $userIds)
  {
    return $this->getOrderTeam($filters, $userIds)->where('status', '!=', 'decline')->sum('grand_total');
  }

  public function sumPricePercentOrderTeam($filters, $userIds)
  {
    return $this->getOrderTeam($filters, $userIds)->where('status', '!=', 'decline')->sum('price_percent');
  }


  public function getUserData($filters, $userIds, $groupBy)
  {
    return User::select('id', 'name')->with(['ref_order_packages' => function ($q) use ($filters, $groupBy) {
      $q->filtertime($filters);
      $q->select(
        'id',
        'ref_id',
        DB::raw("CAST((SUM(grand_total))  AS INTEGER) as  grand_total_sum"),
        DB::raw("CAST((SUM(price_percent))  AS INTEGER) as  price_percent_sum"),
        DB::raw('DATE_FORMAT(created_at, "%M") as month'),
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as time'),
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
  
    foreach ($ranges as $date) {
      foreach ($collection as $item) {
        if (count($item->ref_order_packages) == 0) {
          $item->ref_order_packages[] = array(
            'ref_id' => $item->id,
            'price_percent_sum' => 0,
            'grand_total_sum' => 0,
            'time' => Carbon::parse($date)->format('Y-m-d'),
            'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
          );
        } else {
          if ($type == 'month') {
            $filtered = $item->ref_order_packages->where('month', date("m", strtotime($date)));
          } else {
            $filtered = $item->ref_order_packages->where('time', date("Y-m-d", strtotime($date)));
          }
          if (count($filtered) == 0) {
            $item->ref_order_packages[] = array(
              'ref_id' => $item->id,
              'grand_total_sum' => 0,
              'price_percent_sum' => 0,
              'time' => Carbon::parse($date)->format('Y-m-d'),
              'month' => Carbon::create()->month(date("m", strtotime($date)))->format("M")
            );
          }
        }
      }
    }
    return $collection;
  }
}
