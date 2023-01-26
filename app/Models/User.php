<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User.
 *
 * @property        int                                                                        $id
 * @property        string                                                                     $name
 * @property        string                                                                     $email
 * @property        null|\Illuminate\Support\Carbon                                            $email_verified_at
 * @property        string                                                                     $password
 * @property        null|\Illuminate\Support\Carbon                                            $created_at
 * @property        null|\Illuminate\Support\Carbon                                            $updated_at
 * @property        \App\Models\PersonalAccessToken[]|\Illuminate\Database\Eloquent\Collection $tokens
 * @method   static \Database\Factories\UserFactory                                            factory(...$parameters)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                 newModelQuery()
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                 newQuery()
 * @method   static \Illuminate\Database\Query\Builder|User                                    onlyTrashed()
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                 query()
 * @method   static \Illuminate\Database\Query\Builder|User                                    withTrashed()
 * @method   static \Illuminate\Database\Query\Builder|User                                    withoutTrashed()
 * @mixin \Eloquent
 * @property        null|string                                                                                               $remember_token
 * @property        \Illuminate\Notifications\DatabaseNotification[]|\Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property        null|int                                                                                                  $notifications_count
 * @property        null|int                                                                                                  $tokens_count
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereCreatedAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereEmail($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereEmailVerifiedAt($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereId($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereName($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                wherePassword($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereRememberToken($value)
 * @method   static \Illuminate\Database\Eloquent\Builder|User                                                                whereUpdatedAt($value)
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isEmailVerified(): bool
    {
        return null !== $this->email_verified_at;
    }
}
