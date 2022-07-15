<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thematique extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'nom_en',
        'image_src',
        'schema',
        'color',
        'ordre',
    ];

    public function sousThematiques()
    {
        return $this->hasMany(SousThematique::class);
    }
}
