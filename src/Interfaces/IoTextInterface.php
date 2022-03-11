<?php

namespace FaustVik\Files\Interfaces;

/**
 * Interface IoTextInterface
 * @package FaustVik\Files\interfaces
 */
interface IoTextInterface extends IoInterface
{
    public function readFileToArray(): array;

    public function readFileToString($offset = null, $length = null): string;

    public function overwriteToFile(string $text): void;

    public function appendToFile(string $text): void;
}
