<?php

namespace App\Support;

use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Support\Facades\Http;

class GoogleMapsLink
{
    /** Hanya alamat Google yang boleh diakses server (mencegah SSRF). */
    public static function isAllowed(string $url): bool
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (($parts['scheme'] ?? '') !== 'https' || isset($parts['port']) || isset($parts['user'])) {
            return false;
        }

        return in_array($host, ['maps.app.goo.gl', 'goo.gl'], true)
            || preg_match('/^((www|maps|consent)\.)?google\.(com|co\.id)$/', $host) === 1;
    }

    /** Ikuti redirect link pendek sampai ketemu URL yang memuat koordinat. */
    public static function read(string $url): array
    {
        $data = self::parse($url);

        for ($i = 0; $i < 5 && $data['latitude'] === null && self::isAllowed($url); $i++) {
            $response = Http::withOptions(['allow_redirects' => false])->timeout(10)->get($url);
            $location = $response->header('Location');

            if (! $location) {
                break;
            }

            $url = (string) UriResolver::resolve(new Uri($url), new Uri($location));
            $next = self::parse($url);

            foreach ($next as $key => $value) {
                $data[$key] = $value ?? $data[$key];
            }
        }

        return $data;
    }

    /** Ambil nama, alamat, dan koordinat dari teks URL. */
    public static function parse(string $url): array
    {
        $data = ['spa_name' => null, 'address' => null, 'latitude' => null, 'longitude' => null];

        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);

        // Halaman persetujuan cookie menyimpan URL asli di parameter "continue"
        if (parse_url($url, PHP_URL_HOST) === 'consent.google.com' && is_string($query['continue'] ?? null)) {
            return self::parse($query['continue']);
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $number = '(-?\d+(?:\.\d+)?)';
        $pair = "/^\s*{$number}\s*,\s*{$number}\s*$/";

        // Nama tempat (kadang sekaligus alamat: "Nama, Jalan, Kota")
        $text = null;
        if (preg_match('#/maps/(?:place|search)/([^/@]+)#', $path, $m)) {
            $text = urldecode($m[1]);
        } elseif (is_string($query['q'] ?? null)) {
            $text = $query['q'];
        }

        // Judul yang berupa angka koordinat atau format derajat bukan nama tempat
        if ($text !== null && ! preg_match($pair, $text) && ! str_contains($text, '°')) {
            [$name, $address] = array_pad(explode(',', $text, 2), 2, null);
            $data['spa_name'] = trim($name);
            $data['address'] = $address === null ? null : trim($address);
        }

        // Urutan prioritas: titik pin, koordinat di link, lalu pusat tampilan peta
        $coords = null;
        if (preg_match_all("/!3d{$number}!4d{$number}/", $url, $all, PREG_SET_ORDER)) {
            $coords = end($all);
        } else {
            foreach (['q', 'query', 'll'] as $key) {
                if (is_string($query[$key] ?? null) && preg_match($pair, $query[$key], $m)) {
                    $coords = $m;
                    break;
                }
            }

            if (! $coords && preg_match("#/maps/(?:place|search)/{$number},\s*{$number}#", urldecode($path), $m)) {
                $coords = $m;
            }

            if (! $coords && preg_match("/@{$number},{$number}/", $url, $m)) {
                $coords = $m;
            }
        }

        if ($coords && abs((float) $coords[1]) <= 90 && abs((float) $coords[2]) <= 180) {
            $data['latitude'] = round((float) $coords[1], 8);
            $data['longitude'] = round((float) $coords[2], 8);
        }

        return $data;
    }
}
