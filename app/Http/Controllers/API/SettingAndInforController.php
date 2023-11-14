<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Http\Resources\FAQsResource;
use App\Http\Resources\TermsConditionResource;
use Illuminate\Http\Request;
use Modules\Landingpage\app\Models\Contact;
use Modules\Landingpage\app\Models\QuestionAnswer;
use Modules\Landingpage\app\Models\TermsConditions;

class SettingAndInforController extends Controller
{
    public function get(){
        $term =TermsConditions::first();
        return $term? new TermsConditionResource($term):null;
    }

    public function FAQs()
    {
        return FAQsResource::collection(QuestionAnswer::get());
    }

    public function contact()
    {
        $contact =Contact::first();

        return $contact ? new ContactResource($contact) :null;
    }
}
