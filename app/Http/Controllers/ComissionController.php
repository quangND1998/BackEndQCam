<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\CommissionSetting;
use App\Models\CommissionType;
use App\Models\UserType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Order\Repositories\CommissionRepository;
class ComissionController extends Controller
{
    public function create (){
        $userType = UserType::get();
        $commissionType = CommissionType::with('participants.commission')->get();
        return Inertia::render('Commission/Create', compact('userType','commissionType'));
    }
    public function policy(Request $request){
        $commissionSettings = CommissionSetting::orderBy('created_at','desc')->paginate(10);
        return Inertia::render('Commission/Policy', compact('commissionSettings'));
    }
    public function policyDetail(Request $request,$id){
         $userType = UserType::get();
         $commissionType = CommissionType::with('participants.commission')->get();
         $commissionSetting = CommissionSetting::with('commission')->findOrFail($id);

        // return $commissionSetting;
         return Inertia::render('Commission/Index', compact('userType','commissionType','commissionSetting'));
    }

    public function index (Request $request){
        // dd($request);
        $userType = UserType::get();
        $commissionType = CommissionType::with('participants.commission')->get();
        // return $commissionType;
        $commissionSetting = CommissionSetting::with('commission')->orderBy('created_at','desc')
        ->fillter($request->only( 'from', 'to'))->first();

        // return $commissionSetting;

        return Inertia::render('Commission/Index', compact('userType','commissionType','commissionSetting'));
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
        // dd($request);
        $this->validate(
            $request,
            [
                'commission' => 'required',
                'fromDate' => 'required',
                'toDate' => 'required'
            ]
        );
        $commissionSettingNew = CommissionSetting::with('commission')->where('dateFrom',$request->fromDate)->where('dateTo',$request->toDate)->first();
        if(!$commissionSettingNew){
            $commissionSettingNew = new CommissionSetting;
        }
        $commissionSettingNew->dateFrom = $request->fromDate;
        $commissionSettingNew->dateTo = $request->toDate;
        $commissionSettingNew->level_revenue = $request->level_revenue;
        $commissionSettingNew->save();

        // dd($request);
        $commissionSettingNew->commission()->delete();


        foreach($request->commission as $commissionType){
            foreach($commissionType as $commission){
                foreach($commission as $com){
                    if($com['commission'] > 0){
                    $commission=   Commission::create([
                            'spend_from'=> $com['spend_from'],
                            'spend_to'=> $com['spend_to'],
                            'commission'=> $com['commission'],
                            'commission_type_id' => $com['commissionType'],
                            'user_type_id'=> $com['participant'], //ref
                            'commissionSetting_id' => $commissionSettingNew->id
                        ]);
                        // $commissionSetting->commission()->attach($commission);
                    }
                }
            }
        }

        return redirect('/commission/policy/'.$commissionSettingNew->id);
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
    public function changeStatusPolicy(Request $request, $id){

        $commission = CommissionSetting::find($id);
        if($commission){
             $commission->update([
                'status' => $request->status
            ]);

            return back()->with('success', 'Thay đổi trạng thái thành công');
        }else{
            return back()->with('success', 'Không tồn tại');
        }
    }
    public function destroyCommissionSetting(Request $request, $id){
        $commission = CommissionSetting::find($id);
        if($commission){
             $commission->delete();
            return back()->with('success', 'xóa chính sách thành công');
        }else{
            return back()->with('success', 'Không tồn tại');
        }
    }
}


