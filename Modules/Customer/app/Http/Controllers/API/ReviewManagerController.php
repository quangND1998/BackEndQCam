<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\API\Base2Controller;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\ReviewManagement;
use Modules\Order\app\Models\Order;

class ReviewManagerController extends Base2Controller
{

    public function saveApp(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'star' => 'required',
            'evaluate' => 'nullable',
            'data' => 'nullable',
            'description' => 'nullable|string',
            'images' => 'nullable',
            'images.*' => 'mimes:jpeg,png,jpg|max:2048',
            'product_service_owner_id' => 'required'
        ], [
            'star.required' => 'Vui lòng đánh giá'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        $product_serve_owner = ProductServiceOwner::find($request->product_service_owner_id);
        if ($product_serve_owner && $product_serve_owner->state = 'active') {

            $review = ReviewManagement::create([
                'evaluate' => $request->evaluate,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'star' => $request->star,
                'data' => $request->data,

            ]);
            if ($request->images) {
                foreach ($request->images as $image) {
                    $review->addMedia($image)->toMediaCollection('review_images');
                }
            }
        }


        return $this->sendResponse('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
    }


    public function saveOrder(Request $request, $id)
    {

        $order = Order::find($id);

        if ($order == null) {
            return $this->sendError('Không tìm thấy đơn hàng.', 404);
        }
        $validator = Validator::make($request->all(), [

            'star' => 'required',
            'evaluate' => 'nullable',
            'data' => 'nullable',
            'description' => 'nullable|string',
            'images' => 'nullable',
            'images.*' => 'mimes:jpeg,png,jpg|max:2048',

        ], [
            'star.required' => 'Vui lòng đánh giá'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        if ($order->reviews) {
            $order->reviews->update([
                'evaluate' => $request->evaluate,
                'description' => $request->description,
                'star' => $request->star,
                'data' => $request->data,
            ]);

            if ($request->images) {
                $order->reviews->clearMediaCollection('review_images');
                foreach ($request->images as $image) {
                    $order->reviews->addMedia($image)->toMediaCollection('review_images');
                }
            }
        } else {
            $review = ReviewManagement::create([
                'evaluate' => $request->evaluate,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'star' => $request->star,
                'data' => $request->data,
                'order_id' => $order->id,
            ]);

            if ($request->images) {
                foreach ($request->images as $image) {
                    $review->addMedia($image)->toMediaCollection('review_images');
                }
            }
        }

        return $this->sendResponse('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
    }


    public function getReviewOrder($id)
    {
        $order = Order::with('reviews')->find($id);

        if ($order == null) {
            return $this->sendError('Không tìm thấy đơn hàng.', 404);
        }
        return response()->json($order, 200);
    }




    public function get()
    {
        $reviews = ReviewManagement::paginate(15);
        return Inertia::render('', compact('reviews'));
    }
}
