<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ComplaintManagement;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
class ComplaintController extends Controller
{
    public function index(Request $request){
        $filters = $request->all('search');
        $complains = ComplaintManagement::with('user','product_service_owner.order_package','product_service_owner.product','counselor_staff')->orderBy('created_at','desc')->paginate(20);
        //  return $complains;
        $statusGroup = $this->groupByStatus();
        // return $statusGroup;
        return Inertia::render('Home/Complain/Index', compact('filters', 'complains','statusGroup'));
    }
    public function groupByStatus()
    {
        $array_status = ['no_process','pending','planning','complete', 'cancel'];

        $statusGroup = ComplaintManagement::select('status', DB::raw('count(*) as total'))->groupBy('status')->get();

        foreach ($array_status as $status) {

            $filtered = $statusGroup->where('status', $status)->first();

            if ($filtered == null) {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => 0,
                );
            } else {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => $filtered->total,
                );

            }
        }
        return $newCollections;
    }
}
