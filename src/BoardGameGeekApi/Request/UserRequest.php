<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class UserRequest implements RequestInterface
{
    private string $username;
    private ?string $buddies = null;
    private ?string $guilds = null;
    private string $page = '1';
    private ?string $top = null;
    private ?string $hot = null;

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
        return array_filter([
            self::PARAM_USER_NAME => $this->username,
            self::PARAM_USER_BUDDIES => $this->buddies,
            self::PARAM_USER_GUILDS => $this->guilds,
            self::PARAM_PAGE => $this->page,
            self::PARAM_USER_TOP => $this->top,
            self::PARAM_USER_HOT => $this->hot,
        ]);
    }

    public function buddies(): self
    {
        $this->buddies = '1';

        return $this;
    }

    public function guilds(): self
    {
        $this->guilds = '1';

        return $this;
    }

    public function page(int $page): self
    {
        $this->page = (string) $page;

        return $this;
    }

    public function top(): self
    {
        $this->top = '1';

        return $this;
    }

    public function hot(): self
    {
        $this->hot = '1';

        return $this;
    }
}
