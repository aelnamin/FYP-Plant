<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Add request identifiers that make intermittent Vercel failures
     * traceable without logging request bodies, cookies, or credentials.
     *
     * @return array<string, mixed>
     */
    protected function context(): array
    {
        $request = request();

        return array_merge(parent::context(), [
            'vercel_request_id' => $request->header('x-vercel-id'),
            'request_method' => $request->method(),
            'request_path' => $request->path(),
            'route_name' => $request->route()?->getName(),
        ]);
    }

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $statusCode = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            if ($statusCode < 500) {
                return;
            }

            $request = request();
            $route = $request->route();

            // Keep this entry intentionally short and do not attach the
            // exception object. It is emitted before Laravel's full exception
            // report, so the useful cause remains visible if Vercel truncates
            // the later stack trace.
            Log::error('PRODUCTION_EXCEPTION_SUMMARY', [
                'exception_class' => $e::class,
                'exception_message' => Str::limit($e->getMessage(), 1000),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                'status_code' => $statusCode,
                'request_method' => $request->method(),
                'request_path' => $request->path(),
                'route_name' => is_object($route) && method_exists($route, 'getName')
                    ? $route->getName()
                    : null,
                'vercel_request_id' => $request->header('x-vercel-id'),
                'vercel_region' => getenv('VERCEL_REGION') ?: null,
                'php_memory_peak_mb' => round(memory_get_peak_usage(true) / 1048576, 1),
            ]);
        });
    }
}
