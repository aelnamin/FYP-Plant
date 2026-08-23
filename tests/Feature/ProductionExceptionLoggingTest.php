<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class ProductionExceptionLoggingTest extends TestCase
{
    public function test_unhandled_server_error_writes_a_short_diagnostic_summary(): void
    {
        Route::get('/_test/server-error', static function (): void {
            throw new RuntimeException('Synthetic server failure');
        })->name('test.server-error');

        Log::spy();

        $this->withHeader('x-vercel-id', 'test-vercel-request-id')
            ->get('/_test/server-error')
            ->assertStatus(500);

        Log::shouldHaveReceived('error')
            ->withArgs(function (string $message, array $context): bool {
                return $message === 'PRODUCTION_EXCEPTION_SUMMARY'
                    && $context['exception_class'] === RuntimeException::class
                    && $context['exception_message'] === 'Synthetic server failure'
                    && $context['status_code'] === 500
                    && $context['request_path'] === '_test/server-error'
                    && $context['route_name'] === 'test.server-error'
                    && $context['vercel_request_id'] === 'test-vercel-request-id'
                    && isset($context['exception_file'], $context['exception_line'])
                    && ! array_key_exists('request_body', $context)
                    && ! array_key_exists('cookies', $context);
            })
            ->once();
    }
}
