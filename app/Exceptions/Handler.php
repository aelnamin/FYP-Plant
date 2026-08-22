<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
            //
        });
    }
}
