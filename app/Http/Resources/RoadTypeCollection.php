<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoadTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'=>RoadTypeResource::collection($this->collection),
            'count'=>$this->total(),
            'last_page'=>$this->lastPage(),
            'current_page'=>$this->currentPage(),
            'first_page'=>$this->firstItem(),
            'next_page_url'=>$this->nextPageUrl(),
            'previous_page_url'=>$this->previousPageUrl(),
        ];
    }
}
