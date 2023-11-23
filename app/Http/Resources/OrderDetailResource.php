<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'order_number' => $this->order_number,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'item_count' => $this->item_count,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'grand_total' => $this->grand_total,
            'discount' => $this->discount,
            'shipping_fee' => $this->shipping_fee,
            'last_price' => $this->last_price,
            'address' => $this->address,
            'city' => $this->city,
            'district' => $this->district,
            'wards' => $this->wards,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vat' => $this->vat,
            'discount_deal' => $this->discount_deal,
            'amount_paid' => $this->amount_paid,
            'type' => $this->type,
            'amount_paid' => $this->amount_paid,
            'amount_unpaid' => $this->amount_unpaid,
            'amount_paid' => $this->amount_paid,
            'product_service' => $this->product_service,
            'order_items' => $this->orderItems,

        ];
    }
}
