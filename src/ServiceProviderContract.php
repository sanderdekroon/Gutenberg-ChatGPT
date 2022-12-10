<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT;

interface ServiceProviderContract
{
    public function boot(array $constructArgs): void;
    public function register(): void;
}
