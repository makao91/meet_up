<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
#[OAT\Schema(
    schema: 'Resources\User\UserDetails',
    properties: [
        new OAT\Property(
            property: 'id',
            ref: '#/components/schemas/id',
        ),
        new OAT\Property(
            property: 'email',
            type: 'string',
            example: 'test@devpark.pl',
        ),
        new OAT\Property(
            property: 'name',
            type: 'string',
            example: 'John',
        ),
    ]
)]
class UserDetails extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'email' => $this->resource->email,
            'name' => $this->resource->name,
        ];
    }
}
