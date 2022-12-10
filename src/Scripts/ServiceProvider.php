<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT\Scripts;

use Sanderdekroon\GutenbergGPT\Container;
use Sanderdekroon\GutenbergGPT\AssetLoader;
use Sanderdekroon\GutenbergGPT\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected AssetLoader $assetLoader;

    public function __construct(Container $container, AssetLoader $assetLoader)
    {
        parent::__construct($container);

        $this->assetLoader = $assetLoader;
    }

    public function register(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'loadAdminScripts']);
    }

    public function loadAdminScripts(): void
    {
        wp_enqueue_style(
            'sdk-gutenberggpt-components',
            $this->assetLoader->getUrl('css/admin.css'),
            [],
            $this->container->get('plugin.version')
        );

        wp_enqueue_script(
            'sdk-gutenberggpt-components',
            $this->assetLoader->getUrl('js/admin.js'),
            ['jquery', 'wp-blocks', 'wp-element', 'wp-editor'],
            $this->container->get('plugin.version'),
            true
        );

        wp_localize_script(
            'sdk-gutenberggpt-components',
            'sdk_gutenberggpt',
            apply_filters('sdk-guteberggpt_script_data', [])
        );
    }
}
