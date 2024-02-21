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
use Modules\Customer\app\Models\ComplaintManagement;
use Modules\Customer\app\Models\ProductServiceOwner;

class ComplaintController extends Base2Controller
{
    public function save(Request $request)
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
            'star.required' => 'Vui lòng đánh giá',
            'product_service_owner_id.required' => 'Vui lòng chọn gói sản phẩm '
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        $number = 100000;

        $product_service_owner = ProductServiceOwner::find($request->product_service_owner_id);
        if ($product_service_owner && $product_service_owner->state == 'active') {
            $complaint =  ComplaintManagement::create([
                'evaluate' => $request->evaluate,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'star' => $request->star,
                'data' => $request->data,
                'resource' => 'app',
                'code' => 'KN' . ($number + ComplaintManagement::count() + 1),
                'product_service_owner_id' => $product_service_owner->id
            ]);
            if ($request->images && $complaint) {
                foreach ($request->images as $image) {
                    $complaint->addMedia($image)->toMediaCollection('complaint_images');
                }
            }
            return response()->json('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
        } else {

            return response()->json('Không tìm thấy gói sản phẩm hoặc gói chưa active', 400);
        }
        return response()->json('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
    }


    public function get()
    {
        $complaints = ComplaintManagement::paginate(15);
        return Inertia::render('', compact('complaints'));
    }
}
