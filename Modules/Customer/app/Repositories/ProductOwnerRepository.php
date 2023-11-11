<?php

namespace App\Repositories;
use App\Models\User;
use Modules\Customer\app\Models\ProductServiceOwner;

class ProductOwnerRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new ProductServiceOwner();
    }

    /**
     * Get list Block
     *
     * @var Illuminate\Http\Request $request
     *
     * @var $id (Project)
     *
     *
     *
     * @return back
     */

    public function getListProductOwner($id)
    {

        return $this->model()->with('product','history_product')->get();
    }
}
