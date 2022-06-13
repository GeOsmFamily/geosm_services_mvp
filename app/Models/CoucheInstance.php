<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoucheInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'couche_id',
        'instance_id',
        'opacite',
        'qgis_url',
        'bbox',
        'projection',
        'number_features',
        'vues',
        'surface',
        'distance',
        'telechargement',
    ];
}
