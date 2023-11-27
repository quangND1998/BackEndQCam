<?php

namespace Modules\Tree\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\ProductService;
use App\Http\Controllers\API\Base2Controller;
class ProductServiceController extends Base2Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tree::index');
    }
    public function listProduct()
    {
        $product_service = ProductService::with('images')->get();
        $response = [
            'product_service' =>$product_service,
        ];
        return  $this->sendResponse($response, 'Get product_service successfully');
    }
}
