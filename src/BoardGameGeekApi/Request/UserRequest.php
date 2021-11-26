<?php

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class UserRequest implements RequestInterface
{
    private string $username;
    private ?bool $buddies = null;
    private ?bool $guilds = null;
    private int $page = 1;
    private ?bool $top = null;
    private ?bool $hot = null;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getType(): string
    {
        return self::TYPE_USER;
    }

    public function getParams(): array
    {
        return [
            self::PARAM_USER_NAME => $this->username,
            self::PARAM_USER_BUDDIES => $this->buddies,
            self::PARAM_USER_GUILDS => $this->guilds,
            self::PARAM_PAGE => $this->page,
            self::PARAM_USER_TOP => $this->top,
            self::PARAM_USER_HOT => $this->hot,
        ];
    }

    public function buddies(): self
    {
        $this->buddies = true;

        return $this;
    }

    public function guilds(): self
    {
        $this->guilds = true;

        return $this;
    }

    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function top(): self
    {
        $this->top = true;

        return $this;
    }

    public function hot(): self
    {
        $this->hot = true;

        return $this;
    }
}
