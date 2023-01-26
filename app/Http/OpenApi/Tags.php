<?php

declare(strict_types=1);

namespace App\Http\OpenApi;

use OpenApi\Attributes as OAT;

#[OAT\Tag(
    name: Tags::AUTH,
    description: 'Auth part of application.'
)]
#[OAT\Tag(
    name: Tags::CANDIDATE,
    description: '',
)]
#[OAT\Tag(
    name: Tags::HIRING_MANAGER,
    description: '',
)]
#[OAT\Tag(
    name: Tags::USER,
    description: 'Common part for both Candidate and Hiring Manager.',
)]
class Tags
{
    public const AUTH = 'Auth';
    public const CANDIDATE = 'Candidate';
    public const HIRING_MANAGER = 'Hiring Manager';
    public const USER = 'User';

    public const PRIZE = 'Prize';
}
