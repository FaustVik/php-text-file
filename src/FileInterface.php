<?php

namespace FaustVik\Files;

/**
 * Interface FileInterface
 * @package FaustVik\Files
 */
interface FileInterface
{
    /**
     * Get name File
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get size file (bytes)
     *
     * @return int
     */
    public function getSize(): int;

    /**
     * Get extension file
     *
     * @return string
     */
    public function getExtension(): string;

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