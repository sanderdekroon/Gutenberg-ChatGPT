<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT;

class Plugin
{
    protected Container $container;

    public function setup(Container $container = null): Container
    {
        $this->container = $container ?: new Container();
        // And this is were the magic happens ( ͡° ͜ʖ ͡°)
        $this->container->set(Container::class, fn($container) => $container);

        $pluginConfig = new ConfigFileLoader(dirname(__DIR__) . '/config/container.php');

        foreach ($pluginConfig->get() as $abstract => $value) {
            $this->container->set($abstract, $value);
        }

        return $this->container;
    }

    public function boot(): void
    {
        $this->registerServiceProviders();
    }

    protected function registerServiceProviders(): void
    {
        $this->container->get(ChatGPT\ServiceProvider::class)->register();
        $this->container->get(Scripts\ServiceProvider::class)->register();
    }
}
