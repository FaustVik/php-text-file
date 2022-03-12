<?php

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\ReadFile;
use FaustVik\Files\Helpers\FileMode;
use FaustVik\Files\Interfaces\IoTextInterface;
use FaustVik\Files\Interfaces\LockingInterface;

class TextFile extends BaseFile implements IoTextInterface
{
    public function readFileToArray(): array
    {
        $st         = microtime(true);
        $handle     = $this->openFile($this->pathFile, FileMode::ONLY_READ_BINARY);
        $arrayLines = $this->readArray($handle);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;
        return $arrayLines;
    }

    public function readFileToString(?int $offset = null, ?int $length = null): string
    {
        $st         = microtime(true);
        $handle     = $this->openFile($this->pathFile, FileMode::ONLY_READ_BINARY);
        $arrayLines = $this->readString($handle, $offset, $length);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;
        return $arrayLines;
    }

    public function overWrite(string $text): void
    {
        // TODO: Implement overwriteToFile() method.
    }

    public function write(string $text): void
    {
        // TODO: Implement appendToFile() method.
    }

    /**
     * @param resource $handle
     *
     * @return array
     * @throws FileException
     * @throws ReadFile
     */
    protected function readArray($handle): array
    {
        $result = [];
        $this->baseRead($handle, $result);
        return $result;
    }

    /**
     * @param resource $handle
     * @param int|null $offset
     * @param int|null $length
     *
     * @return string
     * @throws FileException
     * @throws ReadFile
     */
    protected function readString($handle, ?int $offset = 0, ?int $length = null): string
    {
        $result = '';
        $this->baseRead($handle, $result);

        if ($offset !== 0 && $offset !== null && $length !== null && $length !== 0) {
            $result = substr($result, $offset, $length);
        } elseif ($offset !== 0 && $offset !== null) {
            $result = substr($result, $offset);
        }

        return $result;
    }

    /**
     * @param resource     $handle
     * @param array|string $result
     *
     * @return array|string
     * @throws FileException
     * @throws ReadFile
     */
    protected function baseRead($handle, &$result)
    {
        if (!$handle || !is_resource($handle)) {
            throw new FileException('is not resource');
        }

        $this->locking($handle, LockingInterface::BLOCK_READ);

        while (($buffer = fgets($handle)) !== false) {
            if (is_array($result)) {
                $buffer = rtrim($buffer);
            }

            if ($this->skipEmptyLine && empty($buffer)) {
                continue;
            }

            if (is_array($result)) {
                $result[] = $buffer;
            } else {
                $result .= $buffer;
            }
        }

        if (!feof($handle)) {
            throw new ReadFile('Error while reading file');
        }

        $this->unlocking($handle);
        return $result;
    }
}
