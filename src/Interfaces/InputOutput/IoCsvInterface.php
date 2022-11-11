<?php

declare(strict_types=1);

namespace FaustVik\Files\Interfaces\InputOutput;

/**
 * Interface IoCsvInterface
 * @package FaustVik\Files\Interfaces\InputOutput
 */
interface IoCsvInterface extends IoInterface
{
    /**
     * @param array  $fields
     * @param string $separator
     *
     * @return bool
     */
    public function write(array $fields, string $separator = ','): bool;

    /**
     * @param int      $length
     * @param string   $separator
     * @param int|null $line
     *
     * @return array
     */
    public function read(int $length = 0, string $separator = ',', ?int $line = null): array;

    /**
     * @param array  $fields
     * @param string $separator
     *
     * @return bool
     */
    public function overWrite(array $fields, string $separator = ','): bool;
}
