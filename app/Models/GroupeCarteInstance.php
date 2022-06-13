<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupeCarteInstance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_carte_id',
        'instance_id'
    ];
}
