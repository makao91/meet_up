<?php

declare(strict_types=1);

namespace App\Http\OpenApi;

use OpenApi\Attributes as OAT;

#[OAT\Server(
    url: 'http://localhost',
    description: 'localhost'
)]
#[OAT\Server(
    url: 'https://api-talently-dev.devpark.com.pl/',
    description: 'develop'
)]
#[OAT\Server(
    url: 'https://api-talently-stage.devpark.com.pl',
    description: 'staging'
)]
class Servers
{
}
