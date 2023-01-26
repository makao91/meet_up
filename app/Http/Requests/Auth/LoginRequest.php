<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use OpenApi\Attributes as OAT;
use App\Contracts\ILoginRequest;
use Illuminate\Foundation\Http\FormRequest;

#[OAT\Schema(
    schema: 'Requests\Auth\LoginRequest',
    required: [
        'email',
        'password',
    ],
    properties: [
        new OAT\Property(
            property: 'email',
            description: 'Email.',
            type: 'string',
            format: 'email',
            maxLength: 255,
            example: 'test@devpark.pl',
        ),
        new OAT\Property(
            property: 'password',
            description: 'Password.',
            type: 'string',
            format: 'password',
            example: 'test123'
        ),
    ]
)]
class LoginRequest extends FormRequest implements ILoginRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function getEmail(): string
    {
        return $this->input('email');
    }

    public function getPlainPassword(): string
    {
        return $this->input('password');
    }
}
