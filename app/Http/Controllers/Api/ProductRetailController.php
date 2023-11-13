<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Base2Controller;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductRetailCollection;
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
}
