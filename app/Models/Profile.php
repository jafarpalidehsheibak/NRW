<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
protected $fillable = [
    'phone_number',
    'user_id',
    'expert_id',
    'province_id',
];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function expert()
    {
     return $this->belongsTo(Expert::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
