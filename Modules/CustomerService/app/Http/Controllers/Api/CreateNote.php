<?php

namespace Modules\CustomerService\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CreateNote extends Controller
{
  public function __invoke(Request $request)
  {
    $this->validate($request, [
      'note' => 'nullable|string',
    ]);

    $customer = User::findOrFail($request->customerId);
    $customer->update([
      'note' => $request->note
    ]);

    return response()->json([
      'message' => 'OK'
    ]);
  }
}
