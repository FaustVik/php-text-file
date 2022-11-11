<?php

declare(strict_types=1);

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\IsNotResource;
use FaustVik\Files\Interfaces\LockingInterface;

use function flock;
use function is_resource;

/**
 * The base class for performing file locking when an operation (read/write).
 * You can create your own by inheriting from LockingInterface
 *
 * @see     LockingInterface
 *
 * Class LockDefault
 * @package FaustVik\Files\File
 */
final class LockDefault implements LockingInterface
{
    /**
     * @param resource $stream
     * @param int      $operation
     *
     * @return bool
     * @throws IsNotResource
     */
    public function lock($stream, int $operation): bool
    {
        $this->checkResource($stream);
        return flock($stream, $operation);
    }

    /**
     * @param resource $stream
     *
     * @return bool
     * @throws IsNotResource
     */
    public function unlock($stream): bool
    {
        $this->checkResource($stream);
        return flock($stream, self::OPERATION_UNLOCKING);
    }

    /**
     * @param resource $stream
     *
     * @return void
     * @throws IsNotResource
     */
    protected function checkResource($stream): void
    {
        if (!is_resource($stream)) {
            throw new IsNotResource();
        }
    }
}
