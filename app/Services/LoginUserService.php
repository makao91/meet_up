<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Entities\LoginResponse;
use App\Contracts\ILoginRequest;
use App\Contracts\ILoginResponse;
use App\Models\PersonalAccessToken;
use Illuminate\Hashing\HashManager;
use App\Exceptions\Http\NotFoundException;
use App\Exceptions\Http\EmailNotVerifiedException;
use App\Exceptions\Http\InvalidLoginCredentialsException;

final class LoginUserService
{
    public function __construct(
        private readonly User $user_model,
        private readonly PersonalAccessToken $token_model,
        private readonly HashManager $hash_manager,
        private readonly TokenService $token_service,
    ) {
    }

    public function login(ILoginRequest $request): ILoginResponse
    {
        $user = $this->user_model
            ->newQuery()
            ->where('email', $request->getEmail())
            ->first()
        ;

        if (!$user || !$this->areCredentialsValid($user, $request)) {
            throw new InvalidLoginCredentialsException();
        }

        if (!$user->isEmailVerified()) {
            throw new EmailNotVerifiedException();
        }

        $token = $this->token_service->createToken(
            $user,
            ['*']
        );

        return new LoginResponse($user->id, $token);
    }

    public function getUserDetails(int $user_id): User
    {
        /** @var null|User $user */
        $user = $this->user_model->newQuery()
            ->find($user_id)
        ;

        if (!$user) {
            throw new NotFoundException(
                __('User with id: :user_id not found.', ['user_id' => $user_id])
            );
        }

        return $user;
    }

    public function logout(string $token): void
    {
        $access_token = $this->token_model::findToken($token);

        if (!$access_token) {
            return;
        }

        $access_token->forceDelete();
    }

    private function areCredentialsValid(User $user, ILoginRequest $request): bool
    {
        return $this->hash_manager->check($request->getPlainPassword(), $user->getAuthPassword());
    }
}
