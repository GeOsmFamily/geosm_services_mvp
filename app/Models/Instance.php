<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'geom',
        'mapillary',
        'comparator',
        'altimetrie',
        'download',
        'routing',
        'app_version',
        'app_github_url',
        'app_email',
        'app_whatsapp',
        'app_facebook',
        'app_twitter',
    ];

    public function groupesCartes()
    {
        return $this->belongsToMany(GroupeCarte::class, 'groupe_carte_instances', 'instance_id', 'group_carte_id');
    }

    public function couches()
    {
        return $this->belongsToMany(Couche::class, 'couche_instances', 'instance_id', 'couche_id');
    }

    public function thematiques()
    {
        return $this->belongsToMany(Thematique::class, 'thematique_instances', 'instance_id', 'thematique_id');
    }

    public function sousThematiques()
    {
        return $this->belongsToMany(SousThematique::class, 'sous_thematique_instances', 'instance_id', 'sous_thematique_id');
    }

    public function cartes()
    {
        return $this->belongsToMany(Carte::class, 'carte_instances', 'instance_id', 'carte_id');
    }
}
