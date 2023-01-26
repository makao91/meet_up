<?php

declare(strict_types=1);

namespace App\Http\Requests;

trait PaginatedListTrait
{
    protected function getPaginationRules(): array
    {
        return [
            'page' => ['integer', 'gte: 1'],
            'per_page' => ['integer', 'gte: 1'],
        ];
    }

    public function getPerPage(): int
    {
        return (int) $this->input('per_page', config('pagr.default_prizes_pagination'));
    }

    public function getPage(): int
    {
        return (int) $this->input('page', 1);
    }
}
