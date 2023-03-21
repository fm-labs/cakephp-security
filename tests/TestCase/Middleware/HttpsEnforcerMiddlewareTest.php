<?php
declare(strict_types=1);

namespace Security\Test\TestCase\Middleware;

use Cake\TestSuite\TestCase;
use Security\Middleware\HttpsEnforcerMiddleware;

/**
 * Security\Middleware\HttpsEnforcerMiddleware Test Case
 */
class HttpsEnforcerMiddlewareTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Security\Middleware\HttpsEnforcerMiddleware
     */
    protected $HttpsEnforcer;

    /**
     * Test process method
     *
     * @return void
     * @uses \Security\Middleware\HttpsEnforcerMiddleware::process()
     */
    public function testProcess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
