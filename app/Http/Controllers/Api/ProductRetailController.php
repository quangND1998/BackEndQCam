<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\Base2Controller;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductRetailCollection;
use App\Http\Resources\ProductRetailResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Tree\app\Models\ProductRetail;

class ProductRetailController extends Base2Controller
{
    public function getProducts()
    {
        $products = ProductRetail::with('images')->where('status', 1)->paginate(15);

        return new ProductRetailCollection($products);
    }


    public function store(Request $request)
    {
    }

    public function productDetail($id)
    {
        $product = ProductRetail::with('images')->where('status',true)->find($id);
        if ($product) {
            return new ProductRetailResource($product);
        } else {
            return $this->sendError('Không tìm thấy sản phẩm', 404);
        }
    }
}
