<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ComplaintManagement;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Modules\Customer\app\Models\ProblemSolution;
use Spatie\Permission\Models\Role;
class ComplaintController extends Controller
{
    public function index(Request $request){
        $filters = $request->all('search');
        $complains = ComplaintManagement::with('user','product_service_owner.order_package','product_service_owner.product','counselor_staff','problem_solution.role')->orderBy('created_at','desc')->paginate(20);
        // return $complains;
        $statusGroup = $this->groupByStatus();
        $roles = Role::all();
        return Inertia::render('Home/Complain/Index', compact('filters', 'complains','statusGroup','roles'));
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
    public function saveSolution(Request $request,$id){
        $this->validate(
            $request,
            [
                'role_id' => 'required',
                'status' => 'required',
                'severity' => 'required'
            ]
        );
        $complain = ComplaintManagement::with('problem_solution')->find($id);
        $complain->status = $request->status;
        $complain->severity = $request->severity;
        $complain->role_id = $request->role_id;
        $complain->save();

        $solution = new ProblemSolution;
        $solution->reason = $request->reason;
        $solution->solution = $request->solution;
        $solution->detail = $request->detail;
        $solution->complaint_management_id = $complain->id;
        $solution->role_id = $request->role_id;
        $solution->save();

        $complain = ComplaintManagement::with('problem_solution')->find($id);
        return $complain;

    }
}
