<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

final readonly class UrlGenerator implements UrlGeneratorInterface
{
    private const string URL = 'https://api.geekdo.com/xmlapi2';
    private const array DEFAULT_VALUE_FIXES = [
        RequestInterface::PARAM_QUERY => ['/:|!|,/', ''],
    ];

    /** @param array<string, string[]> $valueFixes */
    public function __construct(private string $baseUrl = self::URL, private array $valueFixes = self::DEFAULT_VALUE_FIXES) {}

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
                [$pattern, $replace] = $this->valueFixes[$key];
                $fixed = preg_replace($pattern, $replace, $value);
                assert(is_string($fixed));
                $params[$key] = $fixed;
            }
        }

        return $params;
    }
}
