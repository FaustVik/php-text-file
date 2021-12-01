<?php

namespace FaustVik\Files\interfaces;

/**
 * Interface MoveInterface
 * @package FaustVik\Files\interfaces
 */
interface MoveInterface
{
    /**
     * Copy current file to new file
     *
     * @param string $new_path
     */
    public function copy(string $new_path): bool;

    /**
     * Rename current file
     *
     * @param string $new_name
     */
    public function rename(string $new_name): bool;

    /**
     * Delete current file
     *
     * @return bool
     */
    public function delete(): bool;
}