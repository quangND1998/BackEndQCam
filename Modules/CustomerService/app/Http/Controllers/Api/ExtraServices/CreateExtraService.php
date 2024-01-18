<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\ExtraServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\VisitExtraService;

class CreateExtraService extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:visit_extra_services,name',
        ]);

        $request->merge([
            'is_active' => true
        ]);
        $visitExtraService = VisitExtraService::create($request->all());

        return response()->json([
            'message' => 'OK',
            'visitExtraService' => $visitExtraService
        ]);
    }
}
