<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DomainException;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class ObjectBuilder implements ObjectBuilderManagerInterface
{
    /** @var iterable<ObjectBuilderInterface> */
    private iterable $builders;

    /** @param iterable<ObjectBuilderInterface> $builders*/
    public function __construct(iterable $builders)
    {
        $this->builders = $builders;
    }

    public function build(RequestInterface $request, string $response): DataTransferObjectInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($request)) {
                return $builder->build($response);
            }
        }

        throw new DomainException('Unknown response');
    }
}
