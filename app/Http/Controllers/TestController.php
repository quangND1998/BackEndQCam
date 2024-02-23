<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    public function index()
    { 
        $ransomNote = "aa";
        $magazine = "aab";
        $new_ransomNote = str_split($ransomNote);
        $new_magazine = str_split($magazine);
        foreach($new_ransomNote as  $note){
           $key= array_search( $note,$new_magazine);
            if(array_search($note,$new_magazine) ==false){
                return false;
            }
            else{
                unset($new_magazine[$key]);
            }
        }
        return true;
    }
}
