<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractPassword extends Model
{
    use HasFactory;
    protected $fillable = [
        'contractor_request_id',
        'password',
    ];
    protected $table = 'contract_password';
}
