<?php

namespace App\Http\Resources\Auth;

use OpenApi\Attributes as OAT;
use App\Http\Resources\User\UserDetails;
use Illuminate\Http\Resources\Json\JsonResource;

#[OAT\Schema(
    schema: 'Resources\Auth\LoginResource',
    properties: [
        new OAT\Property(
            property: 'auth',
            properties: [
                new OAT\Property(
                    property: 'token',
                    description: 'Authentication access token.',
                    type: 'string',
                ),
            ],
            type: 'object'
        ),
        new OAT\Property(
            property: 'user',
            ref: '#/components/schemas/Resources\UserDetails',
        ),
    ]
)]
class LoginResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ILoginResponse $login_response */
        $login_response = $this->resource['login_response'];

        /** @var IUserDetails $user_details */
        $user_details = $this->resource['user_details'];

        return [
            'auth' => [
                'token' => $login_response->getToken(),
            ],
            'user' => new UserDetails($user_details),
        ];
    }
}
