<?php

namespace Sanderdekroon\GutenbergGPT\Tests\Unit;

use WP_Mock;
use WP_Mock\Tools\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        WP_Mock::setUp();
    }

    public function tearDown(): void
    {
        WP_Mock::tearDown();
    }
}
