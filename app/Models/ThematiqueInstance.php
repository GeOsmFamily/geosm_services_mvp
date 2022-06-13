<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThematiqueInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'thematique_id',
        'instance_id'
    ];
}
