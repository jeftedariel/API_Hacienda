<?php

namespace Tests\Parity;

/**
 * Un golden master capturado del API legacy (ver legacy/golden/capture.php).
 */
class GoldenFixture
{
    public function __construct(
        public readonly string $file,
        public readonly string $name,
        public readonly string $compare,
        public readonly string $method,
        public readonly string $bodyMode,
        public readonly ?array $query,
        public readonly ?array $params,
        public readonly ?string $rawBody,
        public readonly ?array $files,
        public readonly int $expectedStatus,
        public readonly ?string $expectedContentType,
        public readonly string $expectedBody,
    ) {}

    public static function load(string $path): self
    {
        $d = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $body = $d['response']['body']
            ?? (isset($d['response']['body_base64']) ? base64_decode($d['response']['body_base64']) : '');

        return new self(
            file: basename($path),
            name: $d['name'],
            compare: $d['compare'] ?? 'exact',
            method: $d['request']['method'] ?? 'POST',
            bodyMode: $d['request']['bodyMode'] ?? 'form',
            query: $d['request']['query'] ?? null,
            params: $d['request']['params'] ?? null,
            rawBody: $d['request']['rawBody'] ?? null,
            files: $d['request']['files'] ?? null,
            expectedStatus: $d['response']['status'],
            expectedContentType: $d['response']['content_type'] ?? null,
            expectedBody: (string) $body,
        );
    }

    /** @return array<string, self> nombre => fixture */
    public static function all(): array
    {
        $fixtures = [];
        foreach (glob(__DIR__.'/../Fixtures/golden/*.json') as $path) {
            $f = self::load($path);
            $fixtures[$f->name] = $f;
        }

        return $fixtures;
    }
}
