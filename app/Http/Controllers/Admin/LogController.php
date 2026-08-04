<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(Request $request): View
    {
        $level = $request->string('level')->lower()->value();

        $entries = $this->parseLogs(500);

        if ($level && $level !== 'all') {
            $entries = array_filter($entries, fn ($e) => $e['level'] === strtoupper($level));
        }

        return view('admin.logs.index', [
            'entries' => array_values($entries),
            'filterLevel' => $level ?: 'all',
            'levels' => ['all', 'debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'],
        ]);
    }

    private function parseLogs(int $limit): array
    {
        $path = storage_path('logs/laravel.log');

        if (! file_exists($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_slice($lines, -$limit);

        $entries = [];
        $current = null;
        $pattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.+)/';

        foreach ($lines as $line) {
            if (preg_match($pattern, $line, $m)) {
                if ($current !== null) {
                    $entries[] = $current;
                }
                $current = [
                    'time' => $m[1],
                    'level' => strtoupper($m[2]),
                    'message' => $m[3],
                    'context' => '',
                ];
            } elseif ($current !== null) {
                $current['context'] .= "\n".$line;
            }
        }

        if ($current !== null) {
            $entries[] = $current;
        }

        return array_reverse($entries);
    }
}
