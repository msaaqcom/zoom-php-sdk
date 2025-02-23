<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Support;

use Illuminate\Support\Collection;
use Msaaq\Zoom\Requests\Request;

class Pagination
{
    /**
     * The next page token is used to paginate through large result sets.
     * A next page token will be returned whenever the set of available results exceeds the current page size.
     * The expiration period for this token is 15 minutes.
     */
    public ?string $next_page_token = null;

    public int $page_count;

    public int $page_size;

    public int $total_records;

    public Collection $items;

    public function __construct(
        array $response,
        private readonly Request $request,
        public string $collectionKey,
        private readonly string $method = 'all',
        ?string $model = null,
    ) {
        foreach ($response as $key => $value) {
            if (! property_exists($this, $key)) {
                continue;
            }

            $this->$key = $value;
        }

        $this->items = collect($response[$this->collectionKey]);

        if ($model) {
            $this->items = $this->items->map(fn ($array) => new $model($array));
        }
    }

    public function hasNextPage(): bool
    {
        return ! empty($this->next_page_token);
    }

    public function next(): self
    {
        return $this->request->{$this->method}([
            'next_page_token' => $this->next_page_token,
            'page_size' => $this->page_size,
        ]);
    }
}
