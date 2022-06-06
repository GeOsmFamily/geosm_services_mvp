<?php

namespace App\Models\Social;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Social\SocialLinkedInAccount
 *
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLinkedInAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLinkedInAccount newQuery()
 * @method static \Illuminate\Database\Query\Builder|SocialLinkedInAccount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLinkedInAccount query()
 * @method static \Illuminate\Database\Query\Builder|SocialLinkedInAccount withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SocialLinkedInAccount withoutTrashed()
 * @mixin \Eloquent
 */
class SocialLinkedInAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'provider_user_id', 'provider'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
