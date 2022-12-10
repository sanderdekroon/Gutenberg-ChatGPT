<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT;

use RuntimeException;

class ConfigFileLoader
{
    protected string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function get(): array
    {
        return $this->load();
    }

    /**
     * Load the config file and return the contents.
     * @throws RuntimeException
     * @return array
     */
    public function load(): array
    {
        $this->ensureFileReadable();

        ob_start();
        /**
         * @psalm-suppress UnresolvableInclude
         */
        $variables = require $this->file;
        $obOutput = ob_get_clean();

        if (! empty($obOutput)) {
            throw new RuntimeException("Loading file {$this->file} caused unexpected output");
        }

        if (! is_array($variables)) {
            throw new RuntimeException("File {$this->file} is not an array");
        }

        return $variables;
    }

    protected function ensureFileReadable(): bool
    {
        if (! file_exists($this->file)) {
            throw new RuntimeException("Unkown file {$this->file}");
        }

        if (! is_readable($this->file)) {
            throw new RuntimeException("Unreadable file {$this->file}");
        }

        return true;
    }
}
