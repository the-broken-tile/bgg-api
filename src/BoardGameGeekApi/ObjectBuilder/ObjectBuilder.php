<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DomainException;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

final class ObjectBuilder
{
    /** @var iterable<ObjectBuilderInterface> */
    private iterable $builders;

    public function __construct(iterable $builders)
    {
        $this->builders = $builders;
    }

    public function build(string $response): DataTransferObjectInterface
    {
        foreach($this->builders as $builder) {
            if ($builder->supports($response)) {
                return $builder->build($response);
            }
        }

        throw new DomainException('Unknown response');
    }
}