<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Couche extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sous_thematique_id',
        'nom',
        'nom_en',
        'geometry',
        'schema_table_name',
        'remplir_color',
        'contour_color',
        'service_carto',
        'identifiant',
        'wms_type',
        'logo',
        'sql',
        'condition',
        'mode_sql',
        'sql_complete',
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

    public function sousThematique()
    {
        return $this->belongsTo(SousThematique::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
