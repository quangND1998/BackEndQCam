<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefOrderPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ref_id'=> $this['ref_id'],
            'grand_total_sum'=> $this['grand_total_sum'],
            'price_percent_sum'=> $this['price_percent_sum'],
            'month'=> Carbon::parse($this['time'])->format('M'),
            'time'=> Carbon::parse($this['time'])->format('Y-m-d'),
        ];
    }
}
