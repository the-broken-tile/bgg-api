<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class CollectionItem
{
    use ImageTrait;
    use NameTrait;

    public int $objectId;
    public string $objectType;
    public string $subType;
    public int $collId;
    public ?int $yearPublished = null;
    public CollectionStatus $status;
    public ?int $numberOfPlays = null;
    public ?string $comment = null;
    public ?CollectionVersion $version = null;
    public ?GameStatistics $stats = null;

    public function __construct(int $objectId, string $objectType, string $subType, int $collId)
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->subType = $subType;
        $this->collId = $collId;
    }
}
