<?php

test('offline fallback page loads successfully', function () {
    $response = $this->get(route('offline'));

    $response->assertOk();
    $response->assertSee('Mode Riset Lapangan');
    $response->assertSee('Catat Ide');
    $response->assertSee('Auto-Sync Aktif');
});

test('pwa web app manifest is valid json and contains required pwa fields', function () {
    $manifestPath = public_path('manifest.webmanifest');
    expect(file_exists($manifestPath))->toBeTrue();

    $content = file_get_contents($manifestPath);
    $data = json_decode($content, true);

    expect($data)->toBeArray()
        ->and($data['name'])->toBe('Research OS — Research Repository & Paper Builder')
        ->and($data['short_name'])->toBe('Research OS')
        ->and($data['start_url'])->toBe('/dashboard')
        ->and($data['display'])->toBe('standalone')
        ->and($data['theme_color'])->toBe('#0f766e')
        ->and(count($data['icons']))->toBeGreaterThanOrEqual(4);
});

test('pwa service worker and icons exist in public directory', function () {
    expect(file_exists(public_path('sw.js')))->toBeTrue()
        ->and(file_exists(public_path('icons/icon-192x192.png')))->toBeTrue()
        ->and(file_exists(public_path('icons/icon-512x512.png')))->toBeTrue()
        ->and(file_exists(public_path('icons/apple-touch-icon.png')))->toBeTrue()
        ->and(file_exists(public_path('icons/icon.svg')))->toBeTrue();
});
