<?php

declare(strict_types=1);

namespace Tests\Smoke\App\Http\Controllers\Auth\RegisterController\Register;

use Illuminate\Support\Str;

trait RegisterControllerTrait
{
    public function invalidEntryDataProvider(): iterable
    {
        yield 'invalid email' => [
            [
                'email' => 'test',
            ],
            'email',
        ];

        yield 'empty name' => [
            [
                'name' => '',
            ],
            'name',
        ];

        yield 'too long name' => [
            [
                'name' => Str::random(256),
            ],
            'name',
        ];

        yield 'password not confirmed' => [
            [
                'password' => 'test',
            ],
            'password',
        ];
    }
}
