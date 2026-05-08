<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuranController extends Controller
{
    private array $mushafs = [
        'hafs' => [
            'name'     => 'Hafs',
            'qiraa'    => 'Asim',
            'rawi'     => 'Hafs',
            'pages'    => 604,
            'count'    => '6,236',
            'system'   => 'Kufi',
            'color'    => '#2C5F2E',
            'accent'   => '#97BC62',
            'desc'     => 'The most widely read Mushaf in the world, used across the majority of Muslim countries.',
        ],
        'warsh' => [
            'name'     => 'Warsh',
            'qiraa'    => "Nafi'",
            'rawi'     => 'Warsh',
            'pages'    => 604,
            'count'    => '6,214',
            'system'   => 'Madani',
            'color'    => '#1B3A6B',
            'accent'   => '#D4AF37',
            'desc'     => "Predominant in North and West Africa, following Imam Nafi'i's transmission.",
        ],
        'qalon' => [
            'name'     => 'Qalun',
            'qiraa'    => "Nafi'",
            'rawi'     => 'Qalun',
            'pages'    => 604,
            'count'    => '6,214',
            'system'   => 'Madani',
            'color'    => '#5C3317',
            'accent'   => '#C9A96E',
            'desc'     => 'Used in Libya and Tunisia, one of the two major transmissions of Imam Nafi.',
        ],
        'douri' => [
            'name'     => 'Douri',
            'qiraa'    => 'Abu Amr',
            'rawi'     => 'Al-Douri',
            'pages'    => 604,
            'count'    => '6,205',
            'system'   => 'Basri',
            'color'    => '#3D2B1F',
            'accent'   => '#B8860B',
            'desc'     => "Transmission of Abu Amr al-Basri via Al-Douri, with the Basri counting system.",
        ],
        'shubah' => [
            'name'     => 'Shubah',
            'qiraa'    => 'Asim',
            'rawi'     => "Shu'bah",
            'pages'    => 604,
            'count'    => '6,236',
            'system'   => 'Kufi',
            'color'    => '#2D2D6B',
            'accent'   => '#9B7FD4',
            'desc'     => "The second major transmission of Imam Asim alongside Hafs, via Shu'bah.",
        ],
    ];

    public function index()
    {
        return view('home', ['mushafs' => $this->mushafs]);
    }

    public function reader(string $mushaf)
    {
        if (!array_key_exists($mushaf, $this->mushafs)) {
            abort(404);
        }

        return view('reader', [
            'mushaf'  => $mushaf,
            'info'    => $this->mushafs[$mushaf],
            'mushafs' => $this->mushafs,
        ]);
    }

    public function serveSvg(Request $request, string $mushaf, int $page)
    {
        if (!array_key_exists($mushaf, $this->mushafs)) {
            abort(404);
        }

        $page    = max(1, min(604, $page));
        $pageNum = str_pad($page, 3, '0', STR_PAD_LEFT);
        $svgPath = public_path("mushafs/{$mushaf}/svg/{$pageNum}.svg");

        // ── Brotli: only serve to browsers that truly support it.
        // Safari (desktop & iOS) advertises "br" in Accept-Encoding but
        // cannot decode Brotli-compressed SVG served via <object> tags —
        // it throws "encoding error". We detect Safari by User-Agent and
        // fall back to plain SVG for it.
        $acceptEncoding = $request->header('Accept-Encoding', '');
        $userAgent      = $request->header('User-Agent', '');
        $isSafari       = $this->isSafari($userAgent);

        $brotliPath = public_path("mushafs/{$mushaf}/svg-br/{$pageNum}.svg.br");

        if (!$isSafari && str_contains($acceptEncoding, 'br') && file_exists($brotliPath)) {
            return response(file_get_contents($brotliPath))
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Encoding', 'br')
                ->header('Vary', 'Accept-Encoding')
                ->header('Cache-Control', 'public, max-age=31536000');
        }

        if (file_exists($svgPath)) {
            return response(file_get_contents($svgPath))
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'public, max-age=31536000');
        }

        // Placeholder when no SVG files are placed yet
        return response($this->generatePlaceholderSvg($mushaf, $page))
            ->header('Content-Type', 'image/svg+xml');
    }

    public function serveJson(string $mushaf, int $page)
    {
        if (!array_key_exists($mushaf, $this->mushafs)) {
            abort(404);
        }

        $page     = max(1, min(604, $page));
        $pageNum  = str_pad($page, 3, '0', STR_PAD_LEFT);
        $jsonPath = public_path("mushafs/{$mushaf}/json/{$pageNum}.json");

        if (file_exists($jsonPath)) {
            return response(file_get_contents($jsonPath))
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'public, max-age=31536000');
        }

        return response()->json(['page' => $page, 'mushaf_id' => $mushaf, 'polygons' => []]);
    }

    /**
     * Detect Safari (desktop or iOS).
     *
     * Safari's UA contains "Safari" but NOT "Chrome" or "Chromium"
     * (Chrome on macOS also contains "Safari" in its UA, so we must
     * exclude Chrome/Chromium explicitly).
     * iOS browsers (even Chrome/Firefox on iOS) use WebKit and behave
     * like Safari for this issue, so we also detect "iPhone"/"iPad".
     */
    private function isSafari(string $userAgent): bool
    {
        $ua = strtolower($userAgent);

        // iOS devices — all browsers on iOS use WebKit / same Brotli limitation
        if (str_contains($ua, 'iphone') || str_contains($ua, 'ipad') || str_contains($ua, 'ipod')) {
            return true;
        }

        // Desktop Safari: has "safari" but NOT "chrome" or "chromium" or "edg"
        if (
            str_contains($ua, 'safari') &&
            !str_contains($ua, 'chrome') &&
            !str_contains($ua, 'chromium') &&
            !str_contains($ua, 'edg') &&
            !str_contains($ua, 'opr') &&
            !str_contains($ua, 'opera')
        ) {
            return true;
        }

        return false;
    }

    private function generatePlaceholderSvg(string $mushaf, int $page): string
    {
        $info    = $this->mushafs[$mushaf];
        $pageNum = str_pad($page, 3, '0', STR_PAD_LEFT);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 480" width="340" height="480">
  <rect width="340" height="480" fill="#fdf8f0" rx="4"/>
  <rect x="10" y="10" width="320" height="460" fill="none" stroke="#c8b88a" stroke-width="1" rx="2"/>
  <rect x="16" y="16" width="308" height="448" fill="none" stroke="#c8b88a" stroke-width="0.5" rx="1"/>
  <text x="170" y="240" font-family="serif" font-size="14" fill="#8b7355" text-anchor="middle">{$info['name']} — Page {$pageNum}</text>
  <text x="170" y="260" font-family="serif" font-size="10" fill="#b0956e" text-anchor="middle">Place SVG files in public/mushafs/{$mushaf}/svg/</text>
</svg>
SVG;
    }
}
