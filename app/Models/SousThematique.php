<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SousThematique extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'nom_en',
        'thematique_id',
        'image_src',
    ];

    public function thematique()
    {
        return $this->belongsTo(Thematique::class);
    }

    public function couches()
    {
        return $this->hasMany(Couche::class);
    }
}
