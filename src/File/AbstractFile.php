<?php

namespace FaustVik\Files\File;

abstract class AbstractFile
{
    /**
     * Try open file
     *
     * @param string $path
     * @param string $mode
     *
     * @return resource
     */
    abstract protected function openFile(string $path, string $mode);

    /**
     * try close file
     *
     * @param resource $stream
     *
     * @return bool
     */
    abstract protected function closeFile($stream): bool;

    /**
     * Clear file (result - empty file)
     *
     * @return bool
     */
    abstract public function clear(): bool;

    /**
     * Create empty file
     *
     * @param string $path
     *
     * @return bool
     */
    abstract public function create(string $path): bool;
}
