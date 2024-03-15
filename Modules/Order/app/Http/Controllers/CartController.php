<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cart;
use Modules\Tree\app\Models\ProductRetail;

class CartController extends Controller
{


    public function getCart(){
        $cart = Cart::getContent();
        $response = [
            'cart' => Cart::getContent(),
            'subTotal' =>Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }
    public function addToCart(Request $request){
        $product = ProductRetail::findOrFail($request->product_id);
        $this->addProduct($product,$request);

        $response = [
            'cart' => Cart::getContent(),
            'subTotal' =>Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }

    public function addProduct($product,$request){
        if($product->checkSale()){
            $sale_event = Sale::with('sale_items')->where('state', 'running')->first();
            $sale_item = $product->sale_items()->where('product_id',$product->id)->first();
            $saleCondition = new \Darryldecode\Cart\CartCondition(array(
                'name' => $sale_event->name,
                'type' => $sale_event->type,
                'value' => -$sale_event->discount . $sale_event->unit,
            ));
            Cart::add(array(
                'id' => $product->id, // inique row ID
                'name' => $product->name,
                'price' => $product->cost,
                'quantity' => $request->quantity_cart,
                'attributes' => array([
                    'image' => $product->first_image->image
                ]),
                'conditions' => $saleCondition
            ));
        }else{
            Cart::add(array(
                'id' => $product->id, // inique row ID
                'name' => $product->name,
                'price' => $product->cost,
                'quantity' => $request->quantity_cart,
                'attributes' => array([
                    'image' => $product->first_image->image
                ]),
                // 'conditions' => $saleCondition
            ));
        }

    }

    public function clearCart(){
        Cart::clear();
        $response = [

            'cart' => Cart::getContent(),
            'subTotal' =>Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }
    public function  updateCart(Request $request){
        // return $request;
        Cart::update($request->product_id, array(
            'quantity' => $request->quantity_cart, // so if the current product has a quantity of 4, another 2 will be added so this will result to 6
        ));
        $response = [

            'cart' => Cart::getContent(),
            'subTotal' =>Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }
    public function removeItem(Request $request){
        Cart::remove($request->product_id);
        $response = [
            'cart' => Cart::getContent(),
            'subTotal' =>Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }
}
