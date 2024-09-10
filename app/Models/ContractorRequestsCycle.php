<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorRequestsCycle extends Model
{
    use HasFactory;
    protected $table = 'contractor_requests_cycle';
    protected $fillable = [
        'contractor_request_id',
        'user_id',
        'checklist_id',
        'checklist_item_detail_id',
    ];
}
