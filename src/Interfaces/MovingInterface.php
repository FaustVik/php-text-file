<?php

namespace FaustVik\Files\Interfaces;

/**
 * Interface MovingInterface
 * @package FaustVik\Files\interfaces
 */
interface MovingInterface
{
    /**
     * Copy current file to new file
     *
     * @param string $newPath
     */
    public function copy(string $newPath): bool;

    /**
     * Rename current file
     *
     * @param string $newName
     */
    public function rename(string $newName): bool;

    /**
     * Delete current file
     *
     * @return bool
     */
    public function delete(): bool;

    /**
     * @param string $newPath
     *
     * @return bool
     */
    public function move(string $newPath): bool;
}
