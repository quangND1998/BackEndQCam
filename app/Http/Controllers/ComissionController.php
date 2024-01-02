<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\CommissionType;
use App\Models\UserType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Order\Repositories\CommissionRepository;
class ComissionController extends Controller
{
    public function new (){
        $commissions= Commission::paginate(15);
        return Inertia::render('Commission/Index', compact('commissions'));
    }
    public function index (){
        $commissions= Commission::orderBy('commission','desc')->orderBy('created_at', 'desc')->paginate(10);
        $userType = UserType::get();
        $commissionType = CommissionType::with('participants.commission')->get();
        // return $commissionType;
        return Inertia::render('Commission/Index', compact('commissions','userType','commissionType'));
    }
    public function saveType(Request $request){
        // dd($request);
        $commissionType = new CommissionType;
        $commissionType->description = $request->description;
        $commissionType->save();
        $commissionType->participants()->attach($request->participants);
        return back()->with('success', 'Tạo mới thành công');
    }
    public function getLeader(){
        $commissions = $this->getType('leader-sale');
        return Inertia::render('Commission/Index', compact('commissions'));
    }
    public function getSale(){
        $commissions = $this->getType('saler');
        return Inertia::render('Commission/Index', compact('commissions'));
    }
    public function getCTV(){
        $commissions = $this->getType('ctv');
        return Inertia::render('Commission/Index', compact('commissions'));
    }
    public function getTelesale(){
        $commissions = $this->getType('telesale');
        return Inertia::render('Commission/Index', compact('commissions'));
    }
    public function getType($type){
        return Commission::where('type',$type)->orderBy('spend_from', 'desc')->orderBy('created_at', 'desc')->paginate(5);
    }
    public function store(Request $request){
        // dd($request);
        $this->validate(
            $request,
            [
                'commission' => 'required',
            ]
        );

        foreach($request->commission as $commission){
            foreach($commission as $com){
                if($com['commission'] > 0){
                    Commission::create([
                        'spend_from'=> $com['spend_from'],
                        'spend_to'=> $com['spend_to'],
                        'commission'=> $com['commission'],
                        'commission_type_id' => $com['commissionType'],
                        'user_type_id'=> $com['participant'], //ref
                    ]);
                }
            }
        }

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
                'level_revenue' => 'required|numeric|gt:0',
                'discount_form_sale' => 'required_if:type,==,ctv',
                'discount_form_manager_sale' => 'required_if:type,==,ctv'
            ]
        );


       $commission->update([
        'spend_from'=> $request->spend_from,
        'spend_to'=> $request->spend_to,
        'commission'=> $request->commission,
        'type'=> $request->type,
        'greater' =>$request->greater,
        'level_revenue' => $request->level_revenue,
        'discount_form_sale' => $request->discount_form_sale,
        'discount_form_manager_sale' => $request->discount_form_manager_sale
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


