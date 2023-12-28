<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class SafetyContractorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>Crypt::encrypt($this->id),
            'name'=>$this->name,
            'username'=>$this->email,
            'phone_number'=>$this->phone_number,
            'expert'=>$this->name_expert,
            'address'=>$this->address,
            'expertId'=>$this->expertId,
        ];
    }
}
