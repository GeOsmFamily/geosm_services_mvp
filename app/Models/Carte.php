<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Carte
 *
 * @property int $id
 * @property int $groupe_carte_id
 * @property string $nom
 * @property string $url
 * @property string|null $image_url
 * @property string|null $type
 * @property string|null $identifiant
 * @property string|null $bbox
 * @property string|null $projection
 * @property string|null $zmax
 * @property string|null $zmin
 * @property string|null $commentaire
 * @property bool $principal
 * @property int $vues
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\GroupeCarte|null $groupeCarte
 * @method static \Illuminate\Database\Eloquent\Builder|Carte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carte newQuery()
 * @method static \Illuminate\Database\Query\Builder|Carte onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Carte query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereBbox($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereCommentaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereGroupeCarteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereIdentifiant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte wherePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereProjection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereVues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereZmax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carte whereZmin($value)
 * @method static \Illuminate\Database\Query\Builder|Carte withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Carte withoutTrashed()
 * @mixin \Eloquent
 */
class Carte extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'groupe_carte_id',
        'nom',
        'url',
        'image_url',
        'type',
        'identifiant',
        'bbox',
        'projection',
        'zmax',
        'zmin',
        'commentaire',
        'principal',
        'vues',
    ];

    public function groupeCarte()
    {
        return $this->belongsTo(GroupeCarte::class);
    }

    public function metadatas()
    {
        return $this->hasOne(Metadata::class);
    }
}
