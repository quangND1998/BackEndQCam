<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductServiceOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            "tree" => new TreeResource($this->tree)
        ]);
        // return [
        //     "id" => $this->id,
        //     "user_id" => $this->user_id,
        //     "product_service_id" => $this->product_service_id,
        //     "time_approve" => $this->time_approve,
        //     "description" => $this->description,
        //     "number_deliveries_current" => $this->number_deliveries_current,
        //     "state" => $this->state,
        //     "visited_time" => $this->visited_time,
        //     "quantity_delivered" => $this->quantity_delivered,
        //     "created_at" => $this->created_at,
        //     "updated_at" => $this->updated_at,
        //     "time_end" => $this->time_end,
        //     "order_id" => $this->order_id,
        //     "price" => $this->price,
        //     "tree" => new TreeResource($this->tree),

        // ];
    }
}
