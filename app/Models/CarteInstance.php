<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarteInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'carte_id',
        'instance_id',
    ];
}
