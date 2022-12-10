<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT;

use RegexIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class AssetLoader
{
    protected string $assetPath;
    protected string $assetUrl;
    protected string $distDirectory = 'assets/dist/';
    protected array $assetMap = [];

    public function __construct(string $assetPath, string $assetUrl)
    {
        $this->assetPath = $assetPath;
        $this->assetUrl = $assetUrl;

        $this->loadAssetMap();
    }

    public function getAbsolutePath(string $filename): string
    {
        return (string) ($this->assetMap[$filename] ?? '');
    }

    public function getRelativePath(string $filename): string
    {
        $absolute = $this->getAbsolutePath($filename);

        return empty($absolute) ? '' : str_replace($this->assetPath, '', $absolute);
    }

    public function getUrl(string $filename): string
    {
        $path = $this->getRelativePath($filename);

        return empty($path) ? '' : rtrim($this->assetUrl, '/') . $path;
    }

    protected function loadAssetMap(): void
    {
        $filemap = $this->getFileMap();

        foreach ($filemap as $path => $file) {
            $name = $this->getNormalizedFilename((array) $file);

            $this->addToAssetMap($name, (string) $path);
        }
    }

    protected function getNormalizedFilename(array $file): string
    {
        [$path, $extension] = $file;
        $filename = basename($path);

        $filenameNoExt = str_replace('.' . $extension, '', $filename);

        $leadingFolder = strrchr(str_replace('/' . $filename, '', $path), '/');

        // If the filename without extension still contains a dot,
        // it must be the content hash generated by Webpack.
        if (strpos($filenameNoExt, '.') !== false) {
            [$filenameNoExt, ] = explode('.', $filenameNoExt);
        }

        return sprintf('%s/%s.%s', ltrim($leadingFolder, '/'), $filenameNoExt, $extension);
    }

    protected function getFileMap(): RegexIterator
    {
        $directory = new RecursiveDirectoryIterator($this->getAbsoluteDistPath());

        return new RegexIterator(
            new RecursiveIteratorIterator($directory),
            '/^.+\.(css|js)$/',
            RecursiveRegexIterator::GET_MATCH
        );
    }

    protected function addToAssetMap(string $name, string $path): AssetLoader
    {
        $this->assetMap[$name] = $path;

        return $this;
    }

    protected function getAbsoluteDistPath(): string
    {
        return sprintf('%s/%s/', rtrim($this->assetPath, '/'), rtrim($this->distDirectory, '/'));
    }
}
