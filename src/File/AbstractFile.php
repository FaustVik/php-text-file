<?php

namespace FaustVik\Files\File;

abstract class AbstractFile
{
    abstract protected function checkFile(string $path): void;

    /**
     * @param string $path
     * @param string $mode
     *
     * @return resource
     */
    abstract protected function openFile(string $path, string $mode);

    /**
     * @param resource $stream
     *
     * @return bool
     */
    abstract protected function closeFile($stream): bool;

    abstract public function clear(): bool;

    abstract public function create(string $path): bool;
}
