<?php

namespace Modules\Landingpage\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return  [
            'title'=>$this->title,
            'type'=>$this->type,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'images' => ImageResource::collection($this->images)
        ];
    }
}
