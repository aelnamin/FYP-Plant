<?php

namespace Tests\Feature;

use Tests\TestCase;

class HttpsUrlGenerationTest extends TestCase
{
    public function test_forwarded_https_requests_generate_secure_form_actions(): void
    {
        $response = $this
            ->withServerVariables([
                'HTTP_HOST' => 'fyp-plant.vercel.app',
                'HTTP_X_FORWARDED_HOST' => 'fyp-plant.vercel.app',
                'HTTP_X_FORWARDED_PORT' => '443',
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'REMOTE_ADDR' => '10.0.0.1',
            ])
            ->get('/');

        $response->assertOk();
        $response->assertSee(
            'action="https://fyp-plant.vercel.app/browse"',
            escape: false
        );
    }
}
