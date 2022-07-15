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

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'geom'
    ];
}
