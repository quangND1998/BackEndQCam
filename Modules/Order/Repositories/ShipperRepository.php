<?php

namespace Modules\Order\Repositories;

use App\Models\User;

class ShipperRepository
{

    function getShipper()
    {
        $shippers = User::role('shipper')->get();
        return $shippers;
    }
}
