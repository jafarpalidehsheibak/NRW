<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;


    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function expert()
    {
     return $this->belongsTo(Expert::class);
    }
}
