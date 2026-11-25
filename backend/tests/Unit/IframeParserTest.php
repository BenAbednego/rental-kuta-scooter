<?php

namespace Tests\Unit;

use App\Services\IframeParser;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IframeParserTest extends TestCase
{
    public function test_parses_valid_google_maps_iframe(): void
    {
        $result = app(IframeParser::class)->parse(
            '<iframe src="https://www.google.com/maps/embed?q=Kuta%20Beach" width="600" height="450" title="Kuta Map"></iframe>'
        );

        $this->assertSame('Google Maps', $result['provider_label']);
        $this->assertSame('www.google.com', $result['host']);
        $this->assertSame(600, $result['width']);
        $this->assertSame(450, $result['height']);
        $this->assertSame('Kuta Map', $result['title']);
        $this->assertSame('Kuta Beach', $result['place_name']);
    }

    public function test_rejects_missing_iframe(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Kode iframe tidak ditemukan');

        app(IframeParser::class)->parse('<div>Reviews</div>');
    }

    public function test_rejects_more_than_one_iframe(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Hanya boleh satu iframe');

        app(IframeParser::class)->parse(
            '<iframe src="https://www.google.com/maps/embed?q=Kuta"></iframe><iframe src="https://www.tripadvisor.com/"></iframe>'
        );
    }

    public function test_rejects_script_widgets(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Hanya kode iframe murni yang didukung');

        app(IframeParser::class)->parse('<script src="https://example.com/widget.js"></script>');
    }

    #[DataProvider('unsafeSources')]
    public function test_rejects_non_https_sources(string $src): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('harus menggunakan URL HTTPS');

        app(IframeParser::class)->parse('<iframe src="'.$src.'"></iframe>');
    }

    public static function unsafeSources(): array
    {
        return [
            'http source' => ['http://www.google.com/maps/embed?q=Kuta'],
            'javascript source' => ['javascript:alert(1)'],
            'data source' => ['data:text/html,hello'],
            'protocol-relative source' => ['//www.google.com/maps/embed?q=Kuta'],
        ];
    }

    public function test_rejects_hosts_outside_the_allowlist_and_lists_allowed_domains(): void
    {
        try {
            app(IframeParser::class)->parse('<iframe src="https://evil.example/reviews"></iframe>');
            $this->fail('An unapproved host should be rejected.');
        } catch (InvalidArgumentException $exception) {
            $this->assertStringNotContainsString('evil.example', $exception->getMessage());
            $this->assertStringContainsString('Domain iframe tidak diizinkan', $exception->getMessage());
            $this->assertStringContainsString('www.google.com', $exception->getMessage());
            $this->assertStringContainsString('www.tripadvisor.com', $exception->getMessage());
        }
    }

    public function test_ignores_onload_and_other_unrecognized_attributes(): void
    {
        $result = app(IframeParser::class)->parse(
            '<iframe src="https://www.youtube.com/embed/Abc_def-123" title="Customer video" width="560" height="315" onload="alert(1)" style="display:none" srcdoc="<script>alert(2)</script>"></iframe>'
        );

        $this->assertSame('https://www.youtube.com/embed/Abc_def-123', $result['src']);
        $this->assertSame('YouTube', $result['provider_label']);
        $this->assertSame('Abc_def-123', $result['youtube_id']);
        $this->assertArrayNotHasKey('onload', $result);
        $this->assertArrayNotHasKey('style', $result);
        $this->assertArrayNotHasKey('srcdoc', $result);
    }
}
