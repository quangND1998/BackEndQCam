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
use Modules\Customer\app\Models\ReviewManagement;
use Modules\Order\app\Models\Order;

class ReviewManagerController extends Base2Controller
{

    public function saveApp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'evaluate' => 'required',
            'description' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        ReviewManagement::create([
            'type' => $request->type,
            'evaluate' => $request->evaluate,
            'description' => $request->description,
            'user_id' => Auth::user()->id
        ]);
        return $this->sendResponse('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
    }


    public function saveOrder(Request $request, $id)
    {

        $order = Order::find($id);

        if ($order == null) {
            return $this->sendError('Không tìm thấy đơn hàng.', 404);
        }
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'evaluate' => 'required',
            'description' => 'required|string',
            'star' => 'required|number'

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        ReviewManagement::create([
            'type' => $request->type,
            'evaluate' => $request->evaluate,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
            'star' => $request->star,
            'order_id' => $order->id,
        ]);
        return $this->sendResponse('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
    }


    public function get()
    {
        $reviews = ReviewManagement::paginate(15);
        return Inertia::render('', compact('reviews'));
    }
}
