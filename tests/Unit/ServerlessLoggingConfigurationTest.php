<?php

namespace Tests\Unit;

use Tests\TestCase;

class ServerlessLoggingConfigurationTest extends TestCase
{
    public function test_vercel_logging_never_uses_the_read_only_storage_directory(): void
    {
        $originalVercel = getenv('VERCEL');

        try {
            putenv('VERCEL=1');
            $_ENV['VERCEL'] = '1';
            $_SERVER['VERCEL'] = '1';

            $logging = require config_path('logging.php');

            $this->assertSame('stderr', $logging['default']);
            $this->assertSame(['stderr'], $logging['channels']['stack']['channels']);
            $this->assertSame('php://stderr', $logging['channels']['stderr']['with']['stream']);
            $this->assertNull($logging['channels']['stderr']['formatter']);
            $this->assertSame('php://stderr', $logging['channels']['emergency']['path']);
        } finally {
            if ($originalVercel === false) {
                putenv('VERCEL');
                unset($_ENV['VERCEL'], $_SERVER['VERCEL']);
            } else {
                putenv("VERCEL=$originalVercel");
                $_ENV['VERCEL'] = $originalVercel;
                $_SERVER['VERCEL'] = $originalVercel;
            }
        }
    }

    public function test_local_logging_keeps_the_normal_file_fallback(): void
    {
        $this->assertSame(
            storage_path('logs/laravel.log'),
            config('logging.channels.emergency.path')
        );
    }
}
