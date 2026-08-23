<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSlowRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('production')) {
            return $next($request);
        }

        $startedAt = hrtime(true);
        $queryCount = 0;
        $queryTimeMs = 0.0;

        DB::listen(static function (QueryExecuted $query) use (&$queryCount, &$queryTimeMs): void {
            $queryCount++;
            $queryTimeMs += $query->time;
        });

        try {
            return $next($request);
        } finally {
            $durationMs = (hrtime(true) - $startedAt) / 1_000_000;
            $thresholdMs = max(100, (int) env('SLOW_REQUEST_THRESHOLD_MS', 2000));

            if ($durationMs >= $thresholdMs) {
                $route = $request->route();

                Log::warning('SLOW_REQUEST_SUMMARY', [
                    'request_method' => $request->method(),
                    'request_path' => $request->path(),
                    'route_name' => is_object($route) && method_exists($route, 'getName')
                        ? $route->getName()
                        : null,
                    'vercel_request_id' => $request->header('x-vercel-id'),
                    'vercel_region' => getenv('VERCEL_REGION') ?: null,
                    'duration_ms' => round($durationMs, 1),
                    'database_query_count' => $queryCount,
                    'database_query_time_ms' => round($queryTimeMs, 1),
                    'application_time_ms' => round(max(0, $durationMs - $queryTimeMs), 1),
                    'php_memory_peak_mb' => round(memory_get_peak_usage(true) / 1048576, 1),
                ]);
            }
        }
    }
}
