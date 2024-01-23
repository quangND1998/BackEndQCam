<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\ProductRetils;

use App\Http\Controllers\Controller;
use Modules\Tree\app\Models\ProductRetail;

class GetProductRetails extends Controller
{
    public function __invoke()
    {
        $productRetails = ProductRetail::where('status', 1)
            ->where('available_quantity', '>', 0)
            ->get();

        return response()->json([
            'message' => 'Ok',
            'productRetails' => $productRetails,
        ]);
    }
}
