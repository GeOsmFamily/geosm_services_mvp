<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\GroupeCarte
 *
 * @property int $id
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupeCarte onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupeCarte whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GroupeCarte withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupeCarte withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Carte[] $cartes
 * @property-read int|null $cartes_count
 */
class GroupeCarte extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'nom_en',
    ];

    public function cartes()
    {
        return $this->hasMany(Carte::class);
    }
}
