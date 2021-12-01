<?php

namespace FaustVik\Files\interfaces;

/**
 * Interface InfoFileInterface
 * @package FaustVik\Files\interfaces
 */
interface InfoFileInterface
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
}