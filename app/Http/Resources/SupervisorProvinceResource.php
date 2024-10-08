<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class SupervisorProvinceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'username'=>$this->email,
            'status'=>$this->status,
            'phone_number'=>$this->phone_number,
            'expert'=>$this->name_expert,
            'province'=>$this->province_name,
            'expertId'=>$this->expertId,
            'provinceId'=>$this->provinceId,
        ];
    }
}
