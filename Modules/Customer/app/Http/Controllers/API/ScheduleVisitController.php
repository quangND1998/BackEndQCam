<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Customer\app\Models\ScheduleVisit;
use App\Models\User;
class ScheduleVisitController extends Controller
{
    //
    public function saveScheduleVisit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_time' => 'required',
            'number_adult' => 'required|number',
            'number_children' => 'nullable|number',
            'product_service_owner_id' => 'required',
            'description' => 'nulable'

        ]);
        //state: pending /confirm /complete
        $visit = ScheduleVisit::create($request->all());
        $visit->state = "pending";
        $visit->save();
        $data['visit'] =  $visit;

        return $this->sendResponse($data, 'Đặt lịch thành công, Hãy theo giõi thông báo và tình trạng lịch đặt');

    }
    public function getsheduleCustomer(Request $request)
    {
        // $token = PersonalAccessToken::findToken(request()->bearerToken());
        // $customer = $token->tokenable;
        $customer = User::with('product_service_owners.visit')->find(22);
        $visits = [];
        foreach ($customer->product_service_owners as $product_owner) {
            $visits[] = $product_owner->visit;
        }

        return $this->sendResponse($visits, 'Đặt lịch thành công, Hãy theo giõi thông báo và tình trạng lịch đặt');

    }

}
