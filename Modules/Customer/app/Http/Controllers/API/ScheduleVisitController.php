<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Customer\app\Models\ScheduleVisit;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\API\Base2Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Tree\app\Models\ProductService;

class ScheduleVisitController extends Base2Controller
{
    //
    public function saveScheduleVisit(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date_time' => 'required|date|after:tomorrow',
                'number_adult' => 'required',
                'product_service_owner_id' => 'required',
            ],
            [
                'date_time.after' => 'Ngày đặt lịch phải là một ngày sau ngày hôm nay',
                'product_service_owner_id.required' => 'Trường gói dịch vụ không được bỏ trống.'
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            //state: pending /confirm /complete
            $customer = Auth::user();
            $product_owner = ProductServiceOwner::findOrFail($request->product_service_owner_id);
            if ($product_owner->visited_time < $product_owner->product->free_visit) {
                $date_time = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');
                $visit = ScheduleVisit::create($request->all());
                $visit->state = "pending";
                $visit->date_time = $date_time;
                $visit->save();
                $data =  $visit;

                return $this->sendResponse($data, 'Đặt lịch thành công, Hãy theo giõi thông báo và tình trạng lịch đặt');
            } else {
                return response()->json('Không còn lượt đặt lịch', 400);
            }
        }
    }
    public function getsheduleCustomer(Request $request)
    {
        $customer = Auth::user();
        $product_owner = $customer->product_service_owners->pluck('id')->toArray();
        $visits = ScheduleVisit::with('product_owner_service.product')->whereHas('product_owner_service.product')->whereIn('product_service_owner_id', $product_owner)->get();
        //    return $visit;

        return $this->sendResponse($visits, 'danh sách lịch tham quan');
    }
    public function  getVisitWithProduct($id)
    {
        $visits = ScheduleVisit::where('product_service_owner_id', $id)->get();
        return $this->sendResponse($visits, 'danh sách lịch tham quan');
    }
}
