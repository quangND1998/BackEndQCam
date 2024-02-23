<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    public function index()
    {
        $arr = array(1, 2, 3, 4, 5);
        $n = count($arr);
        $i = 0;
        $j = $n - 1;
        while ($i != $j) {
            // Swap elements using a temporary variable
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            $i++;
        }
        return $arr;
    }
}
