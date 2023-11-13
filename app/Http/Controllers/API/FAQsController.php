<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FAQsResource;
use Illuminate\Http\Request;
use Modules\Landingpage\app\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;

class FAQsController extends Controller
{
    public function FAQs()
    {
        return FAQsResource::collection(QuestionAnswer::get());
    }
}
