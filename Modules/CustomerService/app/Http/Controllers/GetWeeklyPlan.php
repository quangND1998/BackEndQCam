<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetWeeklyPlan extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Modules/CustomerService/weekly-plan');
    }
}
