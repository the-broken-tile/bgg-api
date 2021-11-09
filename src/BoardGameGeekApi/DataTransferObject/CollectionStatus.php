<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class CollectionStatus
{
    public bool $own;
    public bool $previouslyOwned;
    public bool $forTrade;
    public bool $want;
    public bool $wantToPlay;
    public bool $wantToBuy;
    public bool $wishlist;
    public bool $preOrdered;
    public string $lastModified;
    public ?int $wishlistPriority = null;

    public function __construct(
        bool $own,
        bool $previouslyOwned,
        bool $forTrade,
        bool $want,
        bool $wantToPlay,
        bool $wantToBuy,
        bool $wishlist,
        bool $preOrdered,
        string $lastModified,
        ?int $wishlistPriority
    ) {
        $this->own = $own;
        $this->previouslyOwned = $previouslyOwned;
        $this->forTrade = $forTrade;
        $this->want = $want;
        $this->wantToPlay = $wantToPlay;
        $this->wantToBuy = $wantToBuy;
        $this->wishlist = $wishlist;
        $this->preOrdered = $preOrdered;
        $this->lastModified = $lastModified;
        $this->wishlistPriority = $wishlistPriority;
    }
}