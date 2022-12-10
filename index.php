<?php

/**
 * Plugin Name: Gutenberg - ChatGPT integration
 * Description: Use ChatGPT to write your next blog post. Adds a 'ChatGPT' block to the Gutenberg editor
 * Version: 1.0.1
 * Author: sanderdekroon
 * Author URI: https://sanderdekroon.xyz
 * Requires at least: 6.0
 * Tested up to: 6.1
 */

require __DIR__ . '/vendor/autoload.php';

$plugin = new Sanderdekroon\GutenbergGPT\Plugin();
$container = $plugin->setup();
$plugin->boot();

$GLOBALS['sdk_gutenbergGPT_container'] = $container;

function gutenbergGPTContainer()
{
    return $GLOBALS['sdk_gutenbergGPT_container'] ?? null;
}
