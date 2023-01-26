<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use OpenApi\Attributes as OAT;
use App\Contracts\IConfirmUserEmailRequest;
use Illuminate\Foundation\Http\FormRequest;

#[OAT\Schema(
    schema: 'Requests\Auth\ConfirmUserEmailRequest',
    required: [
        'user_id',
        'token',
    ],
    properties: [
        new OAT\Property(
            property: 'user_id',
            ref: '#/components/schemas/id',
        ),
        new OAT\Property(
            property: 'token',
            description: 'The email verification token.',
            type: 'string',
            example: '6eeb9dfbe480291ee89aac6fc3fd1bd7e6559f06'
        ),
    ],
)]
class ConfirmUserEmailRequest extends FormRequest implements IConfirmUserEmailRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'token' => ['required', 'string'],
        ];
    }

    public function getId(): int
    {
        return (int) $this->input('user_id');
    }

    public function getToken(): string
    {
        return (string) $this->input('token');
    }
}
