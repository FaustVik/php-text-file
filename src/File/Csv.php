<?php

declare(strict_types=1);

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\FileIsNotReadable;
use FaustVik\Files\Exceptions\FileNotFound;
use FaustVik\Files\Exceptions\FileNotSupported;
use FaustVik\Files\Helpers\FileInfo;
use FaustVik\Files\Helpers\FileMode;
use FaustVik\Files\Interfaces\CsvRowManipulation;
use FaustVik\Files\Interfaces\InputOutput\IoCsvInterface;
use FaustVik\Files\Interfaces\LockingInterface;

/**
 * The class is used to read and write to csv files.
 * If you need to work with simple text files, use the TextFile class
 * @see     TextFile
 *
 * Interface Csv
 * @package FaustVik\Files\File
 */
class Csv extends BaseFile implements IoCsvInterface, CsvRowManipulation
{
    /**@var array $listAssKey */
    protected $listAssKey = [];

    protected $skipFirstLine = false;

    protected $useAssFromHeader = false;

    /**
     * @param string $pathFile
     *
     * @throws FileNotSupported
     * @throws FileIsNotReadable
     * @throws FileNotFound
     * @throws FileException
     */
    public function __construct(string $pathFile)
    {
        parent::__construct($pathFile);

        $ext = FileInfo::getExtension($pathFile);

        if ($ext !== 'csv') {
            throw new FileNotSupported($ext . ' this extension not supported');
        }
    }

    /**
     * @param array  $fields
     * @param string $separator
     *
     * @return bool
     * @throws FileException
     */
    public function write(array $fields, string $separator = ","): bool
    {
        echo 'Write:' . PHP_EOL;
        return $this->writeCommon(FileMode::WRITE_APPEND_ONLY, $fields, $separator);
    }

    /**
     * @param array  $fields
     * @param string $separator
     *
     * @return bool
     * @throws FileException
     */
    public function overWrite(array $fields, string $separator = ","): bool
    {
        echo 'overWrite:' . PHP_EOL;
        return $this->writeCommon(FileMode::WRITE_TRUNC_ONLY, $fields, $separator);
    }

    /**
     * The key association for the row elements will be taken from the first row
     *
     * @return self
     */
    public function associationsKeyWithHeaders(): self
    {
        $this->useAssFromHeader = true;
        return $this;
    }

    /**
     * @return array
     * @throws FileException
     */
    public function getHeadersColumn(): array
    {
        $result = $this->read(0, ',', 1);
        return $result[0];
    }

    /**
     * @param array $headers
     *
     * @return bool
     * @throws FileException
     */
    public function updateHeaders(array $headers): bool
    {
        $data = $this->read();

        if ($data === []) {
            return false;
        }

        $data[0] = $headers;
        return $this->overWrite($data);
    }

    /**
     * @param array $columns
     *
     * @return bool
     * @throws FileException
     */
    public function deleteColumn(array $columns): bool
    {
        if ($columns === []) {
            return false;
        }

        $data = $this->read();

        if ($data === []) {
            return false;
        }

        foreach ($columns as $column) {
            foreach ($data as $k => $item) {
                if (isset($item[$column])) {
                    $key = $this->listAssKey[$column] ?? $column;
                    unset($item[$key]);
                    $data[$k] = $item;
                }
            }
        }

        return $this->overWrite($data);
    }

    /**
     * @param array $lines
     *
     * @return bool
     * @throws FileException
     */
    public function deleteLine(array $lines): bool
    {
        if ($lines === []) {
            return false;
        }

        $data = $this->read();

        if ($data === []) {
            return false;
        }

        foreach ($lines as $line) {
            if (isset($data[$line])) {
                unset($data[$line]);
            }
        }

        return $this->overWrite($data);
    }

    /**
     * @param int      $length
     * @param string   $separator
     * @param int|null $line
     *
     * @return array
     * @throws FileException
     */
    public function read(int $length = 0, string $separator = ',', ?int $line = null): array
    {
        echo 'Read' . PHP_EOL;
        $st     = microtime(true);
        $handle = $this->openFile($this->pathFile, FileMode::ONLY_READ_BINARY);
        $result = $this->baseRead($handle, $length, $separator, $line);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;

        return $result;
    }

    /**
     * Replace indexed key on custom key
     *
     * @param array $row
     *
     * @return array
     */
    protected function replaceAssociations(array $row): array
    {
        if ($this->listAssKey === []) {
            return $row;
        }

        foreach ($row as $k => $line) {
            if (isset($this->listAssKey[$k])) {
                unset($row[$k]);
                $row[$this->listAssKey[$k]] = $line;
            }
        }

        return $row;
    }

    /**
     * Set rules for replace indexed key for your custom key
     *
     * @param array $list
     *
     * @return self
     * @example  [1 => 'name']
     *           in result [0 = 'fdg', 2 => 123, 'name' => 'Victor']
     *
     */
    public function setAssociationsIndexKeys(array $list): self
    {
        $this->listAssKey = $list;
        return $this;
    }

    /**
     * @param resource $handle
     * @param int      $length
     * @param string   $separator
     * @param int|null $line
     *
     * @return array
     * @throws FileException
     */
    protected function baseRead($handle, int $length, string $separator, ?int $line = null): array
    {
        $result        = [];
        $useSkipHeader = false;
        $counter       = 0;

        if ($line === 0) {
            return [];
        }

        $this->checkResourceHandle($handle);
        $this->locking($handle, LockingInterface::OPERATION_BLOCK_READ);

        while (($data = fgetcsv($handle, $length, $separator)) !== false) {
            if ($this->skipFirstLine && !$useSkipHeader) {
                $useSkipHeader = true;
                continue;
            }

            if (($line && $line > 0) && $counter === $line) {
                break;
            }

            $result[] = $this->replaceAssociations($data);
            if ($this->useAssFromHeader && $counter === 0) {
                $this->setAssociationsIndexKeys($data);
            }
            $counter++;
        }

        $this->unlocking($handle);
        return $result;
    }

    /**
     * @param string $mode
     * @param array  $fields
     * @param string $separator
     *
     * @return bool
     * @throws FileException
     */
    protected function writeCommon(string $mode, array $fields, string $separator = ","): bool
    {
        $st     = microtime(true);
        $handle = $this->openFile($this->pathFile, $mode);
        $res    = $this->baseWrite($handle, $fields, $separator);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;
        return $res;
    }

    /**
     * @param resource $handle
     * @param array    $fields
     * @param string   $separator
     *
     * @return bool
     * @throws FileException
     */
    protected function baseWrite($handle, array $fields, string $separator): bool
    {
        $this->checkResourceHandle($handle);
        $this->locking($handle, LockingInterface::OPERATION_BLOCK_WRITE);

        if (count($fields) === count($fields, COUNT_RECURSIVE)) {
            $result = fputcsv($handle, $fields, $separator);
        } else {
            $result = true;
            foreach ($fields as $field) {
                if (fputcsv($handle, $field, $separator) === false) {
                    $result = false;
                    break;
                }
            }
        }
        $this->unlocking($handle);
        return (bool)$result;
    }
}
