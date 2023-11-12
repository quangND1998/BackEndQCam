<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as Base2Controller;

class Controller extends Base2Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
