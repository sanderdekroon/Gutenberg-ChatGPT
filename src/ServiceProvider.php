<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT;

abstract class ServiceProvider implements ServiceProviderContract
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->boot(func_get_args());
    }

    public function boot(array $constructArgs): void
    {
        // override
    }

    abstract public function register(): void;
}
