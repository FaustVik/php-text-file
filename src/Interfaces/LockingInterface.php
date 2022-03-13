<?php

namespace FaustVik\Files\Interfaces;

/**
 * Interface LockingInterface
 * @package FaustVik\Files\Interfaces
 */
interface LockingInterface
{
    public const OPERATION_BLOCK_READ  = LOCK_SH;
    public const OPERATION_BLOCK_WRITE = LOCK_EX;
    public const OPERATION_UNLOCKING   = LOCK_UN;

    /**
     * Lock on a file
     *
     * @param resource $stream
     * @param int      $operation
     *
     * @return bool
     */
    public function lock($stream, int $operation): bool;

    /**
     * Unlock on a file
     *
     * @param resource $stream
     *
     * @return bool
     */
    public function unlock($stream): bool;
}
