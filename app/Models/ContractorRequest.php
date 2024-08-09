<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_name',
        'contractor_rank',
        'user_id',
        'province_id',
        'city_id',
        'road_name',
        'expert_id',
        'workshop_location_kilometers',
        'workshop_begin_lat_long',
        'workshop_end_lat_long',
        'workshop_name',
        'full_name_connector',
        'mobile_connector',
        'email_connector',
        'approximate_start_date',
        'workshop_duration',
        'description',
        'status',
        'speed_befor',
        'speed_during',
        't_delay_time',
        'volume',
        'road_id_ref',
        'seen_date',
    ];
}
