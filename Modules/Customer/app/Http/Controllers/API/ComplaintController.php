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

class ComplaintController extends Base2Controller
{
    public function save(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'star' => 'required',
            'data' => 'nullable',
            'description' => 'nullable|string',
        ], [
            'star.required' => 'Vui lòng chọn số sao',
            'star.required' => 'Vui lòng chọn số sao',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        ComplaintManagement::create([
            'type' => $request->type,
            'description' => $request->description,
            'user_id' => Auth::user()->id
        ]);
        return $this->sendResponse('Cảm ơn bạn Góp ý cho chúng tôi!', 200);
    }


    public function get()
    {
        $complaints = ComplaintManagement::paginate(15);
        return Inertia::render('', compact('complaints'));
    }
}
