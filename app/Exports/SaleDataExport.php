<?php 
namespace App\Exports;

use App\Invoice;
use App\Service\Sale\PackageOrderService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleDataExport implements FromView
{
    public $order_packages;
    public function __construct($order_packages,) {
        $this->order_packages= $order_packages;

    }
    public function view(): View
    {   

      
        $order_packages =  $this->order_packages;
        return view('exports.sale-order', compact('order_packages'));
    }
}