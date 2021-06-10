<?php

namespace FaustVik\Files;

/**
 * Class Csv
 * @package FaustVik\Files
 */
class Csv extends AbstractFile
{
    /**
     * Csv constructor.
     *
     * @param string $path_to_file
     *
     * @throws FIleException
     */
    public function __construct(string $path_to_file)
    {
        parent::__construct($path_to_file);

        if ($this->getExtension() !== 'csv') {
            throw new FIleException('Extension not supported');
        }
    }

    /**
     * @inheritDoc
     */
    public function save(): bool
    {
        // TODO: Implement save() method.
    }

    /**
     * @inheritDoc
     */
    public function saveToNewFile(string $path): bool
    {
        // TODO: Implement saveToNewFile() method.
    }
}