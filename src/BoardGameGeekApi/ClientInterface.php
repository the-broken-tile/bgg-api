<?php

namespace TheBrokenTile\BoardGameGeekApi;

interface ClientInterface
{
    public function request(RequestInterface $request): ResponseInterface;
}