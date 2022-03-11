<?php

namespace FaustVik\Files\Interfaces;

interface LockingInterface
{
    public const BLOCK_READ  = LOCK_SH;
    public const BLOCK_WRITE = LOCK_EX;
    public const UNLOCKING   = LOCK_UN;

    /**
     * @param resource $stream
     * @param int      $operation
     *
     * @return bool
     */
    public function lock($stream, int $operation): bool;

    /**
     * @param resource $stream
     *
     * @return bool
     */
    public function unlock($stream): bool;
}
