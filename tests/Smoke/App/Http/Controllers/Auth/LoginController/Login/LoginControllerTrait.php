<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\LoginController\Login;

use App\Models\User;

trait LoginControllerTrait
{
    public function getExpectedJsonStructure(): array
    {
        return [
            'data' => [
                'auth' => [
                    'token',
                ],
                'user' => [
                    'id',
                    'email',
                    'name',
                ],
            ],
        ];
    }

    public function getExpectedLoginJsonStructure(): array
    {
        return $this->getExpectedJsonStructure();
    }

    public function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }
}
