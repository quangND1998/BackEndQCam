<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComissionController extends Controller
{
    public function index (){
        $commissions= Commission::paginate(15);
        return Inertia::render('Commission/Index', compact('commissions'));
    }    

    public function store(Request $request){
        $this->validate(
            $request,
            [
      
                'spend' => 'required|numeric|gt:0',
                'commission' => 'required|numeric|gt:0',
                'type' => 'required',
            ]
        );

        Commission::create([
           
            'spend'=> $request->spend,
            'commission'=> $request->commission,
            'type'=> $request->type,
            'greater' =>$request->greater
        ]);
        return back()->with('success', 'Tạo mới thành công');
    }    


    public function update(Request $request, Commission $commission){
        $this->validate(
            $request,
            [
              
                'spend' => 'required|numeric|gt:0',
                'commission' => 'required|numeric|gt:0',
                'type' => 'required',
            ]
        );


       $commission->update([
            'name'=> $request->name,
            'spend'=> $request->spend,
            'commission'=> $request->commission,
            'type'=> $request->type,
            'greater' =>$request->greater
        ]);
        return back()->with('success', 'Cập nhật thành công');
    }   
    
    public function changeStatus(Request $request, Commission $commission){
        $commission->update([
            'status' => $request->status
        ]);
        return back()->with('success', 'Thay đổi trạng thái thành công');
    }
}


