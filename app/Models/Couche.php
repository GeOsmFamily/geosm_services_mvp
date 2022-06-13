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


    ];

    public function sousThematique()
    {
        return $this->belongsTo(SousThematique::class);
    }

    public function instances()
    {
        return $this->belongsToMany(Instance::class, 'couche_instances', 'couche_id', 'instance_id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
