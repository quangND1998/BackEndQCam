<?php

namespace Modules\Customer\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'sex' => $this->sex,
            'address' => $this->address,
            'city' => $this->city,
            'wards' => $this->wards,
            'district' => $this->district,
            'product_service_owners' => $this->product_service_owners,
            'isHasProductService' => count($this->product_service_owners) > 0 ? true : false,
        ];
    }
}
