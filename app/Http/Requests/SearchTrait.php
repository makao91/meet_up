<?php

declare(strict_types=1);

namespace App\Http\Requests;

trait SearchTrait
{
    protected function getSearchRules(): array
    {
        return [
            'search' => ['nullable', 'string', 'min: '.config('talently.minimum_search_phrase')],
        ];
    }

    public function getSearchPhrase(): null|string
    {
        return $this->input('search', null);
    }
}
