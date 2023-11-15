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
            'min_spend' => $this->min_spend,
            'discount_percentage' => $this->discount_percentage,
            'discount_value' => $this->discount_value,
            'discount_max_value' => $this->discount_max_value,
            'discount_mount' => $this->discount_mount,
            "active" => $this->is_fixed == 1 ? true : false,
            'isExpried' => $this->expires_at >= Carbon::now() ? false : true,
            'expires_at' => $this->expires_at,
            'remaining_days' => Carbon::now()->diffInDays($this->expires_at, false)

        ];
    }
}
