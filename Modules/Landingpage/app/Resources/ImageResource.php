<?php

namespace Modules\Landingpage\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return  [
            'original_url' => $this->original_url
        ];
    }
}
