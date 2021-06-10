<?php

namespace FaustVik\Files;

/**
 * Interface FileInterface
 * @package FaustVik\Files
 */
interface FileInterface
{
    /**
     * Get name File
     * @return string
     */
    public function getName():string;

    /**
     * @return int
     */
    public function getSize():int;

    /**
     * Get extension file
     *
     * @return string
     */
    public function getExtension():string;
}