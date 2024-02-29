<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\VisitExtraService;
use Modules\CustomerService\app\Models\VisitService;

class ServiceExtraController extends Controller
{
    //
    public function createService(Request $request){
        $request->validate([
            'name' => 'required|string|unique:visit_extra_services,name',
        ]);

        $request->merge([
            'is_active' => true
        ]);
        VisitExtraService::create($request->all());

        $extraAll = VisitExtraService::all();

        return $extraAll;

    }
    public function deleteService($id){
        $service = VisitExtraService::find($id);
        $service->delete();

        $extraAll = VisitExtraService::all();

        return $extraAll;
    }
}
