<?php

namespace App\Http\OpenApi\Responses;

use OpenApi\Attributes as OAT;

#[OAT\Response(
    response: 'no_content',
    description: 'No content response.',
)]
class NoContentResponse
{
}
