<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commentaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'commentaire',
        'longitude',
        'latitude',
        'image_url',
        'video_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
