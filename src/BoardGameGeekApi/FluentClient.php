<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Collection;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Game;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchResults;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\User;
use TheBrokenTile\BoardGameGeekApi\Exception\ExceptionInterface;
use TheBrokenTile\BoardGameGeekApi\Request\CollectionRequest;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\UserRequest;

final class FluentClient
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws ExceptionInterface
     */
    public function game(int $id): Game
    {
        $game = $this->client->request(new GameRequest($id))->data;
        \assert($game instanceof Game);

        return $game;
    }

    /**
     * @throws ExceptionInterface
     */
    public function search(string $query, bool $exact = false): SearchResults
    {
        $searchResult = $this->client->request(new SearchRequest($query, $exact))->data;
        \assert($searchResult instanceof SearchResults);

        return $searchResult;
    }

    /**
     * @throws ExceptionInterface
     */
    public function user(string $username): User
    {
        $user = $this->client->request(new UserRequest($username))->data;
        \assert($user instanceof User);

        return $user;
    }

    /**
     * @throws ExceptionInterface
     */
    public function collection(string $username): Collection
    {
        $collection = $this->client->request(new CollectionRequest($username))->data;
        \assert($collection instanceof Collection);

        return $collection;
    }
}
