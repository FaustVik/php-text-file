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
     * Removes an entire columns from a file. The countdown is from 0
     *
     * @param array $columns
     *
     * @return bool
     */
    public function deleteColumn(array $columns): bool;

    /**
     * Removes an entire lines from a file. The countdown is from 0
     *
     * @param array $lines
     *
     * @return bool
     */
    public function deleteLine(array $lines): bool;

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

    /**
     * Return selected array column. The countdown is from 0
     *
     * @param array $columns
     *
     * @return array
     */
    public function getColumns(array $columns): array;

    /**
     * Return selected array line. The countdown is from 0
     *
     * @param array $lines
     *
     * @return array
     */
    public function getLines(array $lines): array;
}
