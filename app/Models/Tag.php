<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'couche_id',
        'key',
        'value',
        'operateur'
    ];

    public function couche()
    {
        return $this->belongsTo(Couche::class);
    }
}
