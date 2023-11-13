<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'discount_amount' => $this->discount_amount,
            'unit' => $this->unit,
            'expires_at' => $this->expires_at,
            'isExpried' => $this->expiry_date >= Carbon::now() ? false : true,
            'products' => $this->products,

        ];
    }
}
