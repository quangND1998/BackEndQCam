<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\ExtraServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\VisitExtraService;

class UpdateExtraService extends Controller
{
    public function __invoke(VisitExtraService $visitExtraService, Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:visit_extra_services,name,' . $visitExtraService->id,
            'is_active' => 'required|boolean'
        ]);

        $visitExtraService->update($request->all());

        return response()->json([
            'message' => 'OK',
        ]);
    }
}
