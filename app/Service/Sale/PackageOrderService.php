<?php
namespace App\Service\Sale;

use App\Models\User;
use Carbon\Carbon;
use Modules\Order\app\Models\OrderPackage;

class PackageOrderService  {

    public $model;
    public function __construct() {
        $this->model = new OrderPackage();
    }
     public function sum(){
        return $this->model->sum('price_percent');
     } 
     
     public function sumbyDay($time){
        return $this->model->whereBetween('created_at', [Carbon::now()->subDay($time), Carbon::now()])->sum('price_percent');
     }
    // Lấy danh sách tổng doanh thu toàn hệ thống theo tuần , tháng,  năm 
     public function sumbyTime($filters){
        if ($filters == 'week') {
            $query=   $this->model->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filters == 'month') {
            $query= $this->model->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($filters== 'year') {
            $query=  $this->model->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
        }
        else{
            $query=  $this->model->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
        return $query;    
    
    }
    // Lấy danh sách doanh thu toàn hệ thống theo tuần , tháng năm 
    public function getSaleData($filters){
      return  User::select('id', 'name','created_byId')->with('team')->whereHas('ref_order_packages.product_service_owner')->withSum(
          ['ref_order_packages' => function($q) use($filters) {
              if ($filters == 'week') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($filters == 'month') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                  
                } elseif ($filters== 'year') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                   
                }
                else{
                  $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                }
          }],
          'price_percent'
      )->withCount(['ref_order_packages'=> function($q) use($filters) {
          if ($filters == 'week') {
              $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters == 'month') {
              $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
              
            } elseif ($filters== 'year') {
              $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
               
            }
            else{
              $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }
      }])->orderBy('ref_order_packages_sum_price_percent', 'desc')->get();
    }
  // Lấy danh sách doanh thu top 10 theo tuần , tháng năm 
    public function getTopTenSale($filters){
        return  User::select('id', 'name','created_byId')->with('team')->whereHas('ref_order_packages.product_service_owner')->withSum(
            ['ref_order_packages' => function($q) use($filters) {
                if ($filters == 'week') {
                    $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                  } elseif ($filters == 'month') {
                    $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    
                  } elseif ($filters== 'year') {
                    $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                     
                  }
                  else{
                    $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                  }
            }],
            'price_percent'
        )->withCount(['ref_order_packages'=> function($q) use($filters) {
            if ($filters == 'week') {
                $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
              } elseif ($filters == 'month') {
                $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                
              } elseif ($filters== 'year') {
                $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                 
              }
              else{
                $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
              }
        }])->orderBy('ref_order_packages_sum_price_percent', 'desc')->take(10)->get();
    }


    // Lấy danh tổng doanh thu của user theo tuần , tháng năm
    public function sumbyTimeUser($time, $user){
        $query = $this->sumbyTime($time)->where('ref_id',$user->id)->get();
        return $query->sum('price_percent');
    }

    
    // Lấy danh sách sale theo team theo tuần , tháng năm
    public function getSaleTeam($filters, $team){
      return  User::select('id', 'name','created_byId')->where('created_byId', $team->id)->with('team')->whereHas('ref_order_packages.product_service_owner')->withSum(
          ['ref_order_packages' => function($q) use($filters) {
              if ($filters == 'week') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($filters == 'month') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                  
                } elseif ($filters== 'year') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                   
                }
                else{
                  $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                }
          }],
          'price_percent'
      )->withCount(['ref_order_packages'=> function($q) use($filters) {
          if ($filters == 'week') {
              $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters == 'month') {
              $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
              
            } elseif ($filters== 'year') {
              $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
               
            }
            else{
              $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }
      }])->orderBy('ref_order_packages_sum_price_percent', 'desc')->take(10)->get();
   }


    // Lấy danh sách sale top 10 trong team theo tuần , tháng năm
    public function getTopTenSaleTeam($filters, $team){
      return  User::select('id', 'name','created_byId')->where('created_byId', $team->id)->with('team')->whereHas('ref_order_packages.product_service_owner')->withSum(
          ['ref_order_packages' => function($q) use($filters) {
              if ($filters == 'week') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($filters == 'month') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                  
                } elseif ($filters== 'year') {
                  $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                   
                }
                else{
                  $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                }
          }],
          'price_percent'
      )->withCount(['ref_order_packages'=> function($q) use($filters) {
          if ($filters == 'week') {
              $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters == 'month') {
              $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
              
            } elseif ($filters== 'year') {
              $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
               
            }
            else{
              $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }
      }])->orderBy('ref_order_packages_sum_price_percent', 'desc')->take(10)->get();
    }


    public function rankingAllServe($time, $user){
      $sum =$this->sumbyTimeUser($time, $user);
      return $this->getSaleData($time)->where('ref_order_packages_sum_price_percent' ,'>=',$sum )->count();
    }


}