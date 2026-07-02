<?php
/**
 * Funciones compartidas por los scripts de captura de golden masters.
 */

function callApi(string $baseUrl, array $case): array
{
    $ch = curl_init();
    $method = $case['method'] ?? 'POST';
    $url = $baseUrl;

    if (! empty($case['query'])) {
        $url .= '?'.http_build_query($case['query']);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    if (($case['bodyMode'] ?? 'form') === 'rawjson') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $case['rawBody']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    } elseif ($method === 'GET') {
        $url .= (str_contains($url, '?') ? '&' : '?').http_build_query($case['params'] ?? []);
    } else {
        curl_setopt($ch, CURLOPT_POST, true);
        $fields = $case['params'] ?? [];
        if (! empty($case['files'])) {
            foreach ($case['files'] as $field => $path) {
                $fields[$field] = new CURLFile($path, 'application/octet-stream', basename($path));
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // multipart
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $ctype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $err = curl_error($ch);

    return ['status' => $status, 'content_type' => $ctype, 'body' => $body, 'curl_error' => $err];
}

function saveFixture(string $outDir, int $seq, array $case, array $resp): void
{
    $fixture = [
        'name' => $case['name'],
        'compare' => $case['compare'] ?? 'exact',
        'notes' => $case['notes'] ?? null,
        'request' => [
            'method' => $case['method'] ?? 'POST',
            'bodyMode' => $case['bodyMode'] ?? 'form',
            'query' => $case['query'] ?? null,
            'params' => $case['params'] ?? null,
            'rawBody' => $case['rawBody'] ?? null,
            'files' => isset($case['files']) ? array_map('basename', $case['files']) : null,
        ],
        'response' => [
            'status' => $resp['status'],
            'content_type' => $resp['content_type'],
        ],
    ];

    $body = $resp['body'];
    if ($body !== false && preg_match('//u', $body)) {
        $fixture['response']['body'] = $body;
    } else {
        $fixture['response']['body_base64'] = base64_encode((string) $body);
    }

    $file = sprintf('%s/%02d-%s.json', $outDir, $seq, $case['name']);
    file_put_contents(
        $file,
        json_encode($fixture, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n"
    );

    $ok = $resp['status'] > 0 ? "HTTP {$resp['status']}" : "CURL ERROR: {$resp['curl_error']}";
    echo str_pad($case['name'], 42)." -> $ok\n";
}

/** Busca recursivamente una clave en la respuesta JSON. */
function extractKey(string $body, string $key)
{
    $data = json_decode($body, true);
    if (! is_array($data)) {
        return null;
    }
    $stack = [$data];
    while ($stack) {
        $cur = array_pop($stack);
        foreach ($cur as $k => $v) {
            if ($k === $key) {
                return $v;
            }
            if (is_array($v)) {
                $stack[] = $v;
            }
        }
    }

    return null;
}
