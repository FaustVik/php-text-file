<?php

declare(strict_types=1);

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\ReadFile;
use FaustVik\Files\Helpers\FileMode;
use FaustVik\Files\Interfaces\InputOutput\IoTextInterface;
use FaustVik\Files\Interfaces\LockingInterface;

/**
 * The class is used to read and write to text files.
 * Does not support write to CSV. Use Csv class for this
 * @see     Csv
 *
 * Class TextFile
 * @package FaustVik\Files\File
 */
class TextFile extends BaseFile implements IoTextInterface
{
    /**
     * @return array
     * @throws FileException
     * @throws ReadFile
     */
    public function readToArray(): array
    {
        $st = microtime(true);
        echo 'ReadToArray:' . PHP_EOL;
        $handle     = $this->openFile($this->pathFile, FileMode::ONLY_READ_BINARY);
        $arrayLines = $this->readArray($handle);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;
        return $arrayLines;
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return string
     * @throws FileException
     * @throws ReadFile
     */
    public function readToString(int $offset = 0, int $length = 0): string
    {
        $st = microtime(true);
        echo 'ReadToString:' . PHP_EOL;
        $handle     = $this->openFile($this->pathFile, FileMode::ONLY_READ_BINARY);
        $arrayLines = $this->readString($handle, $offset, $length);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;
        return $arrayLines;
    }

    /**
     * @param string|array $text
     *
     * @return bool
     * @throws FileException
     * @throws \JsonException
     */
    public function overWrite($text): bool
    {
        echo 'overWrite:' . PHP_EOL;
        return $this->writeCommon($text, FileMode::WRITE_TRUNC_ONLY);
    }

    /**
     * @param string|array $text
     *
     * @return bool
     * @throws FileException|\JsonException
     */
    public function write($text): bool
    {
        echo 'Write:' . PHP_EOL;
        return $this->writeCommon($text, FileMode::WRITE_APPEND_ONLY);
    }

    /**
     * @param string|array $text
     * @param string       $mode
     *
     * @return bool
     * @throws FileException
     * @throws \JsonException
     */
    protected function writeCommon($text, string $mode): bool
    {
        if (is_array($text)) {
            $text = json_encode($text, JSON_THROW_ON_ERROR);
            if ($text === false) {
                throw new FileException('Can\'t json encode array');
            }
        } else {
            try {
                $text = (string)$text;
            } catch (\Throwable $exception) {
                throw new FileException($exception->getMessage());
            }
        }

        $st     = microtime(true);
        $handle = $this->openFile($this->pathFile, $mode);
        $res    = $this->baseWrite($handle, $text);
        $this->closeFile($handle);
        echo (microtime(true) - $st) . PHP_EOL;
        return $res;
    }

    /**
     * @param resource $handle
     * @param string   $text
     *
     * @return bool
     * @throws FileException
     */
    protected function baseWrite($handle, string $text): bool
    {
        $this->checkResourceHandle($handle);
        $this->locking($handle, LockingInterface::OPERATION_BLOCK_WRITE);
        $result = fwrite($handle, $text);
        $this->unlocking($handle);
        return (bool)$result;
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

        if ($this->skipEmptyLine) {
            foreach ($result as $k => $item) {
                if ($item === "\n") {
                    unset($result[$k]);
                }
            }
        }

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
    protected function readString($handle, int $offset = 0, int $length = 0): string
    {
        $result = '';
        $this->baseRead($handle, $result);

        if ($offset !== 0 && $length !== 0) {
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
        $this->checkResourceHandle($handle);
        $this->locking($handle, LockingInterface::OPERATION_BLOCK_READ);

        while (($buffer = fgets($handle)) !== false) {
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
