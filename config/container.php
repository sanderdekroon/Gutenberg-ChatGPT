<?php

use Tectalic\OpenAi\Manager;
use Tectalic\OpenAi\Authentication;
use Sanderdekroon\GutenbergGPT\Container;
use Sanderdekroon\GutenbergGPT\AssetLoader;
use Sanderdekroon\GutenbergGPT\Support\Request;
use Tectalic\OpenAi\Handlers\Completions as GPTCompletions;

return [
    'plugin.name'       => 'Gutenberg - ChatGPT integration',
    'plugin.slug'       => 'sdk-gutenberggpt',
    'plugin.version'    => '1.0.1',
    'plugin.path'       => dirname(__DIR__),
    'plugin.url'        => plugins_url(basename(dirname(__DIR__))),
    'openai.api_key'    => '',

    Request::class => fn() => Request::fromGlobal(),

    AssetLoader::class => function (Container $container): AssetLoader {
        return new AssetLoader(
            $container->get('plugin.path'),
            $container->get('plugin.url'),
        );
    },

    Authentication::class => function (Container $container): Authentication {
        return new Authentication($container->get('openai.api_key'));
    },

    GPTCompletions::class => function (Container $container): GPTCompletions {
        $openaiClient = Manager::build(
            new \GuzzleHttp\Client(),
            $container->get(Authentication::class)
        );

        return $openaiClient->completions();
    },
];
