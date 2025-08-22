<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class CollectionStatus
{
    public function __construct(
        public bool $own,
        public bool $previouslyOwned,
        public bool $forTrade,
        public bool $want,
        public bool $wantToPlay,
        public bool $wantToBuy,
        public bool $wishlist,
        public bool $preOrdered,
        public string $lastModified,
        public ?int $wishlistPriority = null,
    ) {}
}
