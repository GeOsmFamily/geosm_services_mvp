<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'carte_id',
        'couche_id',
        'resume',
        'description',
        'zone',
        'epsg',
        'langue',
        'echelle',
        'licence',
    ];

    public function carte()
    {
        return $this->belongsTo(Carte::class);
    }

    public function couche()
    {
        return $this->belongsTo(Couche::class);
    }
}
