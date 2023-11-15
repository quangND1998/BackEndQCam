<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\SaleService\MoneyDiscount;
use App\SaleService\PercentDiscount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\ProductServiceVoucher;
use Modules\Order\app\Models\Voucher;
use Modules\Tree\app\Models\ProductService;

class ProductServiceVoucherController extends Controller
{


    protected $percent, $money;

    public function __construct(PercentDiscount $percent, MoneyDiscount $money)
    {

        $this->percent = $percent;
        $this->money = $money;
    }
    public function deleteItems(Request $request)
    {
        $ids = $request->ids;

        if ($ids == null) {
            return back()->with('warning', "You must choose in checkbox !!!.");
        }

        $product_service_vouchers = ProductServiceVoucher::whereIn('id',  $ids)->get();
        if ($product_service_vouchers->count() > 0) {
            foreach ($product_service_vouchers as $item) {
                $item->delete();
            }

            return back()->with('success', "Delete changed successfully.");
        }
        return back()->with('warning', "Not found product");
    }

    public function deleteProductServiceVoucher(ProductServiceVoucher $product_service_voucher)
    {
        $product_service_voucher->delete();
        return back()->with('success', "Delete changed successfully.");
    }





    public function saveItems(Request $request, Voucher $voucher)
    {

        $ids = $request->ids;
        if ($ids == null) {
            return back()->with('warning', "You must choose in checkbox !!!.");
        }
        $products = ProductService::whereIn('id',  $ids)->get();

        if ($products->count() > 0) {
            foreach ($products as $product) {

                $item = $voucher->product_service_vouchers()->where('product_service_id', $product->id)->first();

                if ($item) {
                    if ($voucher->unit == "percent") {
                        $this->percent::updateItem($voucher, $item, $product, $voucher->discount_amount);
                    } else {
                        $this->money::updateItem($voucher, $item, $product, $voucher->discount_amount);
                    }
                } else {
                    if ($voucher->unit == "percent") {
                        $this->percent::createProductServiceVoucher($voucher, $product, $voucher->discount_amount);
                    } else {
                        $this->money::createProductServiceVoucher($voucher, $product, $voucher->discount_amount);
                    }
                }
            }

            return back()->with('success', "Đã thêm vào chương trình sale.");
        }

        return back()->with('warning', "Not found product");
    }


    public function updateDiscount(Request $request)
    {
        $this->validate($request, [
            'unit' => 'required',
            'discount' => 'required|numeric|gt:0',
            'item_sale_id' => 'required'
        ]);

        $item = ProductServiceVoucher::find($request->item_sale_id);

        if ($item) {
            $product = ProductService::find($item->product_id);
            $voucher = Voucher::find($item->sale_id);
            if ($product && $voucher) {
                if ($voucher->unit == "percent") {
                    PercentDiscount::updateItem($voucher, $item, $product, $request->discount);
                } else {
                    MoneyDiscount::updateItem($voucher, $item, $product, $request->discount);
                }
                return response()->json($item->load('product'), 200);
            } else {
                return response()->json('Not found', 404);
            }
        } else {
            return response()->json('Not found', 404);
        }
    }
}
