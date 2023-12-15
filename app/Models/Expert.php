<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_expert'
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class);

    }
}
