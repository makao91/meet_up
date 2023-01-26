<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Contracts\ITokenRequest;
use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest implements ITokenRequest
{
    public function rules(): array
    {
        return [
            'authorization' => ['required', 'string'],
        ];
    }

    public function validationData(): array
    {
        return array_merge(
            $this->all(),
            [
                'authorization' => $this->header('authorization'),
            ]
        );
    }

    public function getToken(): string
    {
        return $this->bearerToken();
    }
}
