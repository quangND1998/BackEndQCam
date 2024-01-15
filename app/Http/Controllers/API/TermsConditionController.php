<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TermsConditionResource;
use Illuminate\Http\Request;
use Modules\Landingpage\app\Models\TermsConditions;

class TermsConditionController extends Controller
{
    public function get()
    {
        return new TermsConditionResource(TermsConditions::first());
    }
}
