<?php

namespace Modules\Tree\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\app\Models\RefundProducts;
use Modules\Tree\app\Models\ProductRetail;

class ProductRefundController extends Controller
{
    public function __invoke(Request $request)
    {

        $products = RefundProducts::fillter($request->only('search', 'type', 'fromDate', 'toDate', 'state'))->paginate($request->per_page ? $request->per_page : 10);

        return $products;
    }


    public function confirm($id)
    {
        $refund_product = RefundProducts::find($id);


        if ($refund_product) {
            if ($refund_product->state == 'pending') {
                $refund_product->update([
                    'state' => 'completed'
                ]);
                if ($refund_product->type == 'H') {
                    $product = ProductRetail::find($refund_product->product_id);
                    if ($product) {
                        $product->available_quantity = $product->available_quantity + $refund_product->quantity;
                        $product->save();
                    }
                }
            }
            return response()->json('Xác nhận thành công', 200);
        } else {
            return response()->json('Không tim thấy sản phẩm trong kho', 404);
        }
    }
}
