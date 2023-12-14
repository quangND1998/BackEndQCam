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
                'spend_from' => 'required|numeric|gt:0',
                'spend_to' => 'required|numeric|gt:0',
                'commission' => 'required|numeric|gt:0',
                'type' => 'required',
                'level_revenue' => 'required|numeric|gt:0'
            ]
        );

        Commission::create([
            'spend_from'=> $request->spend_from,
            'spend_to'=> $request->spend_to,
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
                'spend_from' => 'required|numeric|gt:0',
                'spend_to' => 'required|numeric|gt:0',
                'commission' => 'required|numeric|gt:0',
                'type' => 'required',
                'level_revenue' => 'required|numeric|gt:0'
            ]
        );


       $commission->update([
        'spend_from'=> $request->spend_from,
        'spend_to'=> $request->spend_to,
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


