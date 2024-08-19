<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ContractorRequestResource extends JsonResource
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
            "contractor_name"=> $this->contractor_name,
            "contractor_rank"=> $this->contractor_rank,
            "user_id"=> $this->user_id,
            "contractor_mobile"=> $this->email,
            "road_name"=> $this->road_name,
            "workshop_location_kilometers"=> $this->workshop_location_kilometers,
            "workshop_begin_lat_long"=> $this->workshop_begin_lat_long,
            "workshop_end_lat_long"=> $this->workshop_end_lat_long,
            "workshop_name"=> $this->workshop_name,
            "full_name_connector"=> $this->full_name_connector,
            "mobile_connector"=> $this->mobile_connector,
            "email_connector"=> $this->email_connector,
            "approximate_start_date"=> $this->approximate_start_date,
            "workshop_duration"=> $this->workshop_duration,
            "description"=> $this->description,
            "status"=> $this->status,
            "status_name"=> $this->status_name,
            "province_name"=> $this->province_name,
            "city_name"=> $this->city_name,
            "name_expert"=> $this->name_expert
        ];
    }
}
