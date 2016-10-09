<?php

namespace Link0\Tactician;

use League\Tactician\Middleware as TacticianMiddleware;
use League\StatsD\Client as StatsdClient;

final class StatsdMiddleware implements TacticianMiddleware
{
    /**
     * @var StatsdClient $client
     */
    private $client;

    /**
     * @var string $namespace
     */
    private $namespace;

    /**
     * @param StatsdClient $client
     * @param string $namespace
     */
    public function __construct(StatsdClient $client, $namespace)
    {
        $this->client = $client;
        $this->namespace = $namespace;
    }

    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $commandName = strtolower(get_class($command));

        $this->client->increment($this->namespace . $commandName);

        return $next($command);
    }
}
