<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Hashing\HashManager;
use App\Contracts\IRegisterUserRequest;
use App\Contracts\IConfirmUserEmailRequest;
use App\Exceptions\Http\EmailAlreadyExistsException;
use App\Exceptions\Http\InvalidConfirmEmailTokenException;

final class RegisterService
{
    public function __construct(
        private readonly User $user_model,
        private readonly HashManager $hash_manager,
    ) {
    }

    public function register(IRegisterUserRequest $request): User
    {
        $this->validateUserEmail($request->getEmail());

        $user = $this->user_model->newInstance([
            'email' => $request->getEmail(),
            'name' => $request->getName(),
            'password' => $this->makePasswordHash($request->getPlainPassword()),
        ]);
        $user->save();

        return $user;
    }

    public function sendVerifyEmailAfterRegistrationEmail(int $id, string $sha1): void
    {
    }

    public function confirmEmail(IConfirmUserEmailRequest $request): bool
    {
        /** @var null|User $user */
        $user = $this->user_model->newQuery()
            ->whereNull('email_verified_at')
            ->find($request->getId())
        ;

        if (!$user || sha1($user->email) !== $request->getToken()) {
            throw new InvalidConfirmEmailTokenException();
        }

        $user->markEmailAsVerified();

        return true;
    }

    private function validateUserEmail(string $email): void
    {
        $exists = $this->user_model->newQuery()->where('email', $email)->exists();

        if ($exists) {
            throw new EmailAlreadyExistsException();
        }
    }

    private function makePasswordHash(string $plain_password): string
    {
        return $this->hash_manager->make($plain_password);
    }
}
