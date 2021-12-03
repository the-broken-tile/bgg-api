<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchResults;
use TheBrokenTile\BoardGameGeekApi\Request\RetrySearchRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class RetryExactSearchBuilder implements ObjectBuilderInterface
{
    private ObjectBuilderInterface $searchBuilder;

    public function __construct(ObjectBuilderInterface $searchBuilder)
    {
        $this->searchBuilder = $searchBuilder;
    }

    public function supports(RequestInterface $request): bool
    {
        return $request instanceof RetrySearchRequest;
    }

    public function build(string $response, RequestInterface $request): DataTransferObjectInterface
    {
        $result = $this->searchBuilder->build($response, $request);
        \assert($result instanceof SearchResults);

        $result->items = array_values(
            array_filter(
                $result->items,
                static fn (SearchItem $item) => $item->name->value === $request->getParams()[RequestInterface::PARAM_QUERY],
            ),
        );
        $result->total = \count($result->items);

        return $result;
    }
}
