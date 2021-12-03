<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

final class UrlGenerator implements UrlGeneratorInterface
{
    private const URL = 'https://api.geekdo.com/xmlapi2';
    private const DEFAULT_VALUE_FIXES = [
        RequestInterface::PARAM_QUERY => ['/:|!|,/', ''],
    ];

    private string $baseUrl;
    /** @var array<string, string[]> */
    private array $valueFixes;

    /** @param array<string, string[]> $valueFixes */
    public function __construct(string $baseUrl = self::URL, array $valueFixes = self::DEFAULT_VALUE_FIXES)
    {
        $this->baseUrl = $baseUrl;
        $this->valueFixes = $valueFixes;
    }

    public function generate(RequestInterface $request): string
    {
        return sprintf(
            '%s/%s?%s',
            $this->baseUrl,
            $request->getType(),
            http_build_query($this->fixValues($request->getParams())),
        );
    }

    /**
     * @param array<string, string> $params
     *
     * @return array<string, string>
     */
    private function fixValues(array $params): array
    {
        foreach ($params as $key => $value) {
            if (isset($this->valueFixes[$key])) {
                /** @var string $pattern */
                /** @var string $replace */
                [$pattern, $replace] = $this->valueFixes[$key];
                $params[$key] = preg_replace($pattern, $replace, $value);
            }
        }

        return $params;
    }
}
