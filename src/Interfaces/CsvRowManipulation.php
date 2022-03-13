<?php

declare(strict_types=1);

namespace FaustVik\Files\Interfaces;

/**
 * Interface CsvRowManipulation
 * @package FaustVik\Files\Interfaces
 */
interface CsvRowManipulation
{
    /**
     * Removes an entire column from a file. The countdown is from 0
     *
     * @param int $column
     *
     * @return bool
     */
    public function deleteColumn(int $column): bool;

    /**
     * Removes an entire line from a file. The countdown is from 0
     *
     * @param int $line
     *
     * @return bool
     */
    public function deleteLine(int $line): bool;

    /**
     * Updates the first line in a file
     *
     * @param array $headers
     *
     * @return bool
     */
    public function updateHeaders(array $headers): bool;

    /**
     * Gets the first line in a file
     *
     * @return array
     */
    public function getHeadersColumn(): array;
}
