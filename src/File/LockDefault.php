<?php

declare(strict_types=1);

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Interfaces\LockingInterface;

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
     * @throws FileException
     */
    public function lock($stream, int $operation): bool
    {
        $this->checkResource($stream);
        return flock($stream, $operation);
    }

    public function unlock($stream): bool
    {
        $this->checkResource($stream);
        return flock($stream, self::OPERATION_UNLOCKING);
    }

    /**
     * @param resource $stream
     *
     * @return void
     * @throws FileException
     */
    protected function checkResource($stream): void
    {
        if (!$stream || !is_resource($stream)) {
            throw new FileException('Is not type resource');
        }
    }
}
