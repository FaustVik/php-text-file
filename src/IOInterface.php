<?php

namespace FaustVik\Files;

/**
 * Interface IOInterface
 * @package FaustVik\Files
 */
interface IOInterface
{
    /**
     * Saving data to the current file
     *
     * @return bool
     */
    public function save(): bool;

    /**
     * Saving data to a new file
     *
     * @param string $path
     *
     * @return bool
     */
    public function saveToNewFile(string $path): bool;
}