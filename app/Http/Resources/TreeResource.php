<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'history_care' => $this->history_care()->with('activityCare')->select('*', DB::raw('DATE(date) as dategroup'))->orderBy('date', 'desc')->get()->groupBy('dategroup')
        ]);
    }
}
