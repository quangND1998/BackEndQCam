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

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $product_ownwer = ProductServiceOwner::with('customer','contract.history_contact')->findOrfail($id);
        return Inertia::render('Modules/Customer/detail/contract', compact('product_ownwer'));
    }

    public function store(Request $request, $id)
    {
        $product_ownwer = ProductServiceOwner::with('contract')->findOrfail($id);
        if($product_ownwer != null && $product_ownwer->contract){
            $contract = $product_ownwer->contract;
        }else{
            $contract = new Contract;
            $contract->product_service_owner_id = $product_ownwer->id;
            $contract->save();
        }
        $history = new HistoryContract;
        $history->contracts_id = $contract->id;
        $history->save();
        foreach ($request->images as $image) {
            $history->addMedia($image)->toMediaCollection('contract_images');
        }
        return back()->with('success', 'Create successffully');
    }
    public function update(Request $request, $id)
    {
        $contract = Contract::findOrfail($id);
        $contract->clearMediaCollection('contract_images');
        foreach ($request->images as $image) {
            $contract->addMedia($image)->toMediaCollection('contract_images');
        }
        return back()->with('success', 'Update successffully');
    }
        /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

}
