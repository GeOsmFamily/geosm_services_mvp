<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ouvrage extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ouvrage';

    protected $fillable = [
        'nomsyndicat',
        'nomdepartement',
        'nomcommunebe',
        'nomcommunemr',
        'nomcommuneml',
        'nomlocalite',
        'latitude',
        'longitude',
        'numeroreferenceouvrage',
        'nomouvrage',
        'datacollecte',
        'typeouvrage',
        'typepointeau',
        'etatpointeau',
        'etatlatrine',
        'fonctionnel',
        'datemiseoeuvre',
        'sourcefinancement',
        'maitreouvrage',
        'maitreoeuvre',
        'photourl',
        'quantitsuffisanteeau',
        'qualiteeau',
        'etateau',
        'sourcepollution',
        'existancecomitegestion',
        'statutlegal',
        'nomcomite',
        'nompointfocal',
        'contactpointfocal',
        'commentaire',
        'nomvillage'
    ];
}
