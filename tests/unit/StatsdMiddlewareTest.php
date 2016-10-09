<?php

namespace Link0\Tactician;

use PHPUnit_Framework_TestCase;
use Prophecy\Prophet;
use League\StatsD\Client;

final class StatsdMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client $statsd
     */
    private $statsd;

    /**
     * @var StatsdMiddleware
     */
    private $middleware;

    public function setUp()
    {
        $prophet = new Prophet();
        $this->statsd = $prophet->prophesize(Client::class);
        $this->namespace = 'foo.';

        $this->middleware = new StatsdMiddleware(
            $this->statsd->reveal(),
            $this->namespace);
    }

    public function test_command_handling_increments_stat()
    {
        $command = new \stdClass();
        $nextCalled = false;
        $next = function () use (&$nextCalled) {
            $nextCalled = true;
        };

        $this->middleware->execute($command, $next);

        $key = $this->namespace . strtolower(get_class($command));
        $this->statsd->increment($key)->shouldHaveBeenCalled();

        $this->assertTrue($nextCalled);
    }
}
