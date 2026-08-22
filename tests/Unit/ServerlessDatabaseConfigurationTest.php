<?php

namespace Tests\Unit;

use PDO;
use Tests\TestCase;

class ServerlessDatabaseConfigurationTest extends TestCase
{
    public function test_mysql_uses_a_bounded_non_persistent_connection(): void
    {
        $options = config('database.connections.mysql.options');

        $this->assertIsInt($options[PDO::ATTR_TIMEOUT]);
        $this->assertGreaterThan(0, $options[PDO::ATTR_TIMEOUT]);
        $this->assertFalse($options[PDO::ATTR_PERSISTENT]);
    }
}
