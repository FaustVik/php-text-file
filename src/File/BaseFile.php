<?php

declare(strict_types=1);

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\File\FileIsNotReadable;
use FaustVik\Files\Exceptions\File\FileIsNotWriteable;
use FaustVik\Files\Exceptions\File\FileNotFound;
use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Exceptions\IsNotResource;
use FaustVik\Files\Helpers\FileInfo;
use FaustVik\Files\Helpers\FileMode;
use FaustVik\Files\Interfaces\LockingInterface;

use function fclose;
use function fopen;
use function ftruncate;
use function is_file;
use function is_resource;

/**
 * Class AbstractFile
 * @package FaustVik\Files\File
 */
class BaseFile extends AbstractFile
{
    /**@var string $pathFile */
    protected $pathFile;

    /**@var ?LockingInterface $lockHelper */
    protected $lockHelper = null;

    protected $useLockFile = false;

    protected $skipEmptyLine = false;

    /**
     * @param string $pathFile
     * @param bool   $create
     *
     * @throws FileIsNotReadable
     * @throws FileNotFound
     * @throws FileException
     */
    public function __construct(string $pathFile, bool $create = false)
    {
        if ($create && !$this->create($pathFile)) {
            throw new FileException('Can\'t create file');
        }

        $this->checkFile($pathFile);
        $this->setPathFile($pathFile);
    }

    /**
     * @param string $path
     *
     * @return void
     * @throws FileIsNotWriteable
     * @throws FileException
     * @throws FileIsNotReadable
     * @throws FileNotFound
     */
    protected function checkFile(string $path): void
    {
        if (!FileInfo::exist($path)) {
            throw new FileNotFound($path);
        }

        if (!is_file($path)) {
            throw new FileException('Is not file');
        }

        if (!FileInfo::isReadable($path)) {
            throw new FileIsNotReadable($path);
        }

        if (!FileInfo::isWritable($path)) {
            throw new FileIsNotWriteable($path);
        }
    }

    /**
     * @param string $path
     * @param string $mode
     *
     * @return resource
     * @throws FileException
     */
    protected function openFile(string $path, string $mode)
    {
        $handle = fopen($path, $mode);

        if ($handle === false) {
            throw new FileException('Cant open file');
        }

        return $handle;
    }

    /**
     * @param resource $stream
     *
     * @return bool
     * @throws FileException
     */
    protected function closeFile($stream): bool
    {
        $this->checkResourceHandle($stream);
        return fclose($stream);
    }

    /**
     * Enable a lock on a file when performing read or write operations
     *
     * @return $this
     */
    public function enableLockFile(): self
    {
        $this->useLockFile = true;
        return $this;
    }

    /**
     * Add your own class for blocking files inherited from "LockingInterface"
     * by default used LockDefault
     *
     * @param LockingInterface|null $locking
     *
     * @return $this
     * @see LockDefault,LockingInterface
     *
     */
    public function setLocker(LockingInterface $locking = null): self
    {
        if ($locking === null) {
            $this->lockHelper = new LockDefault();
        } else {
            $this->lockHelper = $locking;
        }

        return $this;
    }

    /**
     * wrapper for locking
     *
     * @param     $stream
     * @param int $operation
     *
     * @return void
     * @throws FileException
     */
    protected function locking($stream, int $operation): void
    {
        if (!$this->useLockFile) {
            return;
        }

        if ($this->lockHelper === null) {
            $this->setLocker();
        }

        if (!$this->lockHelper->lock($stream, $operation)) {
            throw new FileException('Cant lock file');
        }
    }

    /**
     * wrapper for unlocking
     *
     * @param $stream
     *
     * @return void
     * @throws FileException
     */
    protected function unlocking($stream): void
    {
        if (!$this->useLockFile) {
            return;
        }

        if ($this->lockHelper === null) {
            $this->setLocker();
        }

        if (!$this->lockHelper->unlock($stream)) {
            throw new FileException('Cant unlock file');
        }
    }

    /**
     * @throws FileException
     */
    public function flush(): bool
    {
        $handle = $this->openFile($this->pathFile, FileMode::WRITE_READ_TRUNC);
        $result = ftruncate($handle, 0);
        $this->closeFile($handle);
        return $result;
    }

    /**
     * Return path to file
     *
     * @return string
     */
    public function getPathFile(): string
    {
        return $this->pathFile;
    }

    /**
     * @param string $pathFile
     */
    protected function setPathFile(string $pathFile): void
    {
        $this->pathFile = $pathFile;
    }

    /**
     * @throws FileException
     */
    public function create(string $path): bool
    {
        if (FileInfo::exist($path)) {
            return true;
        }

        $handle = $this->openFile($path, FileMode::WRITE_TRUNC_ONLY);
        return $this->closeFile($handle);
    }

    /**
     * only works when reading into an array
     *
     * @return $this
     */
    public function skipEmptyLine(): self
    {
        $this->skipEmptyLine = true;
        return $this;
    }

    /**
     * @param $handle
     *
     * @return void
     * @throws IsNotResource
     */
    protected function checkResourceHandle($handle): void
    {
        if (!$handle || !is_resource($handle)) {
            throw new IsNotResource();
        }
    }
}
