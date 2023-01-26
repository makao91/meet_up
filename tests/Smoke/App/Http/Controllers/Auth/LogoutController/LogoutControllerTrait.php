<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\LogoutController;

use App\Models\User;

trait LogoutControllerTrait
{
    public function createAndBeCandidate(array $attributes = [], array $abilities = ['user-type-candidate']): User
    {
        $candidate = parent::createAndBeCandidate($attributes, $abilities);

        $this->createAndUseAccessToken($candidate, abilities: $abilities);

        return $candidate;
    }

    public function createAndBeHiringManager(array $attributes = [], array $abilities = ['user-type-hiring-manager']): User
    {
        $hiring_manager = parent::createAndBeHiringManager($attributes, $abilities);

        $this->createAndUseAccessToken($hiring_manager, abilities: $abilities);

        return $hiring_manager;
    }
}
