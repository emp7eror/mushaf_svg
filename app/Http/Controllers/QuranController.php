<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $acceptEncoding = $request->header('Accept-Encoding', '');
        $brotliPath     = public_path("mushafs/{$mushaf}/svg-br/{$pageNum}.svg.br");
        $svgPath        = public_path("mushafs/{$mushaf}/svg/{$pageNum}.svg");

        if (str_contains($acceptEncoding, 'br') && file_exists($brotliPath)) {
            return response(file_get_contents($brotliPath))
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Encoding', 'br')
                ->header('Cache-Control', 'public, max-age=31536000');
        }

        if (file_exists($svgPath)) {
            return response(file_get_contents($svgPath))
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'public, max-age=31536000');
        }

        // Return a placeholder SVG if file doesn't exist yet
        $placeholder = $this->generatePlaceholderSvg($mushaf, $page);
        return response($placeholder)->header('Content-Type', 'image/svg+xml');
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
