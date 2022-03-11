<?php

namespace FaustVik\Files\File;

use FaustVik\Files\Exceptions\FileException;
use FaustVik\Files\Interfaces\LockingInterface;

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
        return flock($stream, LOCK_UN);
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
