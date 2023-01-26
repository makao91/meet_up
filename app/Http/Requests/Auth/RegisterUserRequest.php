<?php

namespace App\Http\Requests\Auth;

use OpenApi\Attributes as OAT;
use App\Models\Descriptors\UserType;
use Illuminate\Validation\Rules\Enum;
use App\Contracts\IRegisterUserRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

#[OAT\Schema(
    schema: 'Requests\Auth\RegisterUserRequest',
    required: [
        'first_name',
        'last_name',
        'email',
        'password',
        'password_confirmation',
        'type',
    ],
    properties: [
        new OAT\Property(
            property: 'first_name',
            description: 'First name.',
            type: 'string',
            maxLength: 255,
            example: 'Johm',
        ),
        new OAT\Property(
            property: 'last_name',
            description: 'Last name.',
            type: 'string',
            maxLength: 255,
            example: 'Doe',
        ),
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
            example: 'test123',
        ),
        new OAT\Property(
            property: 'password_confirmation',
            description: 'Password confirmation.',
            type: 'string',
            format: 'password',
            example: 'test123',
        ),
        new OAT\Property(
            property: 'type',
            ref: '#/components/schemas/Descriptors\UserType',
            type: 'string',
        ),
    ]
)]
class RegisterUserRequest extends FormRequest implements IRegisterUserRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->symbols()->numbers()],
            'type' => ['required', 'string', new Enum(UserType::class)],
        ];
    }

    public function getName(): string
    {
        return $this->input('name');
    }

    public function getEmail(): string
    {
        return $this->input('email');
    }

    public function getPlainPassword(): string
    {
        return $this->input('password');
    }

    public function getType(): UserType
    {
        $type = $this->input('type');

        return UserType::from($type);
    }
}
