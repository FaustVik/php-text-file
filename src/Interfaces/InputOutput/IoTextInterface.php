<?php

declare(strict_types=1);

namespace FaustVik\Files\Interfaces\InputOutput;

/**
 * Interface IoTextInterface
 * @package FaustVik\Files\Interfaces\InputOutput
 */
interface IoTextInterface extends IoInterface
{
    /**
     * reading a file into an array. each row is a new array element
     *
     * @return array
     */
    public function readToArray(): array;

    /**
     * reading a file into a string, you can control its length with offset and string length
     *
     * @param int $offset
     * @param int $length
     *
     * @return string
     */
    public function readToString(int $offset = 0, int $length = 0): string;

    /**
     * Flush the file and write new $text to the beginning of the file.
     * If the passed text is not an array, then it will be tried to be cast to a string
     *
     * @param string|array $text
     *
     * @return bool
     */
    public function overWrite($text): bool;

    /**
     * $text is append to the end of the file.
     * If the passed text is not an array, then it will be tried to be cast to a string
     *
     * @param string|array $text
     *
     * @return bool
     */
    public function write($text): bool;

    /**
     * Append text to the start of the file
     *
     * @param array|string $text
     *
     * @return bool
     */
    public function appendToStartFile($text): bool;
}
