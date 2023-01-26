<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\PersonalAccessToken.
 *
 * @property        int                                                       $id
 * @property        string                                                    $tokenable_type
 * @property        int                                                       $tokenable_id
 * @property        string                                                    $name
 * @property        string                                                    $token
 * @property        null|array                                                $abilities
 * @property        null|\Illuminate\Support\Carbon                           $last_used_at
 * @property        null|\Illuminate\Support\Carbon                           $created_at
 * @property        null|\Illuminate\Support\Carbon                           $updated_at
 * @property        \Eloquent|\Illuminate\Database\Eloquent\Model             $tokenable
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newModelQuery()
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newQuery()
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken query()
 * @mixin \Eloquent
 * @property        null|\Illuminate\Support\Carbon                           $expires_at
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereAbilities($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereCreatedAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereExpiresAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereId($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereLastUsedAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereName($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereToken($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableId($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableType($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereUpdatedAt($value)
 */
class PersonalAccessToken extends BaseModel
{
    use HasFactory;

    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
    ];
}
