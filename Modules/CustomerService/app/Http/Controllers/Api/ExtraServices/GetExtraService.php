<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\ExtraServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\VisitExtraService;

class GetExtraService extends Controller
{
    public function __invoke()
    {
        $extraServices = VisitExtraService::where('is_active', true)->get();

        return response()->json([
            'message' => 'OK',
            'extraServices' => $extraServices
        ]);
    }
}
