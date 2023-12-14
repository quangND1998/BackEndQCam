<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentReource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'OrderNo'=> $this->OrderNo,
            'OrderCash'=> $this->OrderCash,
            'PaymentStatus'=> $this->PaymentStatus,
            'PaymentMethod'=> $this->PaymentMethod,
            'PaymentMethodName'=> $this->PaymentMethodName,
            'BankName'=> $this->BankName,
            'PurchaseDate'=> Carbon::parse($this->PurchaseDate)->format('Y-m-d H:i:s'),
            'CardNumber'=> $this->CardNumber
        ];
    }
}
