<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SousThematiqueInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sous_thematique_id',
        'instance_id'
    ];
}
