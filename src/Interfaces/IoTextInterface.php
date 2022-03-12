<?php

namespace FaustVik\Files\Interfaces;

/**
 * Interface IoTextInterface
 * @package FaustVik\Files\interfaces
 */
interface IoTextInterface extends IoInterface
{
    public function readFileToArray(): array;

    public function readFileToString(?int $offset = null, ?int $length = null): string;

    public function overWrite(string $text): void;

    public function write(string $text): void;
}
