<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use InvalidArgumentException;

class IframeParser
{
    /**
     * @return array{
     *     src: string,
     *     provider: string,
     *     provider_label: string,
     *     host: string,
     *     width: int|null,
     *     height: int|null,
     *     title: string|null,
     *     place_name: string|null,
     *     youtube_id: string|null
     * }
     */
    public function parse(string $code): array
    {
        $previousState = libxml_use_internal_errors(true);
        $document = new DOMDocument;

        try {
            $loaded = $document->loadHTML(
                '<?xml encoding="UTF-8"><div>'.$code.'</div>',
                LIBXML_NONET | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED
            );
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousState);
        }

        if (! $loaded) {
            throw new InvalidArgumentException('Kode iframe tidak ditemukan');
        }

        $xpath = new DOMXPath($document);

        if ($xpath->query('//script')->length > 0) {
            throw new InvalidArgumentException('Hanya kode iframe murni yang didukung; kode script/widget tidak dapat digunakan.');
        }

        $iframes = $xpath->query('//iframe');

        if ($iframes->length === 0) {
            throw new InvalidArgumentException('Kode iframe tidak ditemukan');
        }

        if ($iframes->length !== 1) {
            throw new InvalidArgumentException('Hanya boleh satu iframe');
        }

        $iframe = $iframes->item(0);
        if (! $iframe instanceof DOMElement) {
            throw new InvalidArgumentException('Kode iframe tidak ditemukan');
        }

        $src = trim($iframe->getAttribute('src'));
        $url = parse_url($src);

        if (
            ! is_array($url)
            || strtolower($url['scheme'] ?? '') !== 'https'
            || empty($url['host'])
            || isset($url['user'])
            || isset($url['pass'])
        ) {
            throw new InvalidArgumentException('Alamat iframe harus menggunakan URL HTTPS yang valid.');
        }

        $host = strtolower(rtrim($url['host'], '.'));
        $providers = config('iframe_providers.domains', []);
        $providerLabel = $providers[$host] ?? null;

        if (! is_string($providerLabel)) {
            $allowedDomains = implode(', ', array_keys($providers));
            throw new InvalidArgumentException("Domain iframe tidak diizinkan. Domain yang boleh: {$allowedDomains}.");
        }

        $placeName = null;
        if (in_array($host, ['google.com', 'www.google.com'], true) && str_contains($url['path'] ?? '', '/maps/embed')) {
            parse_str($url['query'] ?? '', $query);
            $placeName = isset($query['q']) && is_string($query['q']) && $query['q'] !== ''
                ? $query['q']
                : null;
        }

        $youtubeId = null;
        if (in_array($host, ['youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            $path = trim($url['path'] ?? '', '/');
            if (preg_match('#^embed/([A-Za-z0-9_-]{11})$#', $path, $matches) === 1) {
                $youtubeId = $matches[1];
            }
        }

        return [
            'src' => $src,
            'provider' => $providerLabel,
            'provider_label' => $providerLabel,
            'host' => $host,
            'width' => $this->dimension($iframe->getAttribute('width')),
            'height' => $this->dimension($iframe->getAttribute('height')),
            'title' => $this->title($iframe->getAttribute('title')),
            'place_name' => $placeName,
            'youtube_id' => $youtubeId,
        ];
    }

    private function dimension(string $value): ?int
    {
        $value = trim($value);

        if ($value === '' || preg_match('/^\d{1,5}$/', $value) !== 1) {
            return null;
        }

        $dimension = (int) $value;

        return $dimension > 0 ? $dimension : null;
    }

    private function title(string $value): ?string
    {
        $title = trim($value);

        return $title === '' ? null : mb_substr($title, 0, 255);
    }
}
