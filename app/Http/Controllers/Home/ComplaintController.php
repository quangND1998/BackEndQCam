<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ComplaintManagement;
use Inertia\Inertia;
class ComplaintController extends Controller
{
    public function index(Request $request){
        $filters = $request->all('search');
        $complains = ComplaintManagement::with('user')->orderBy('created_at','desc')->paginate(20);
        return Inertia::render('Home/Complain/Index', compact('filters', 'complains'));
    }
}
