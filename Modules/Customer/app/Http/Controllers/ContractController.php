<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\ProductService;
use Modules\Customer\app\Models\ProductServiceOwner;
use Inertia\Inertia;
use Modules\Customer\app\Models\Contract;
use Modules\Customer\app\Models\HistoryContract;
use Modules\Customer\app\Models\HistoryExtend;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $history_extend = HistoryExtend::with('product_service_owner','contract.history_contact.images')->findOrfail($id);
        // return $product_ownwer;
        return Inertia::render('Modules/Customer/detail/contract', compact('history_extend'));
    }

    public function store(Request $request, $id)
    {
        return $request;
        $history_extend = HistoryExtend::with('contract')->findOrfail($id);
        if($history_extend != null && $history_extend->contract){
            $contract = $history_extend->contract;
        }else{
            $contract = new Contract;
            $contract->extend_id = $history_extend->id;
            $contract->save();
        }
        $history = new HistoryContract;
        $history->contracts_id = $contract->id;
        $history->save();
        $history->addMedia($request->images)->toMediaCollection('contract_images');
        return back()->with('success', 'Create successffully');
    }
    public function update(Request $request, $id)
    {
        $contract = Contract::findOrfail($id);
        $contract->clearMediaCollection('contract_images');
        $contract->addMedia($request->images)->toMediaCollection('contract_images');
        return back()->with('success', 'Update successffully');
    }
    public function extend(Request $request, $id){
        
    }
        /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

}
