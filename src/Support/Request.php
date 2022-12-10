<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT\Support;

class Request extends ParameterBag
{
    public static function fromGlobal(): Request
    {
        return new self($_REQUEST);
    }

    public function isPost(): bool
    {
        return $this->getRequestMethod() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->getRequestMethod() === 'GET';
    }

    public function getRequestMethod(): string
    {
        return (string) ($_SERVER['REQUEST_METHOD'] ?? '');
    }

    public function getRequestUri(): string
    {
        return (string) ($_SERVER['REQUEST_URI'] ?? '');
    }
}
