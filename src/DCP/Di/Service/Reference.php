<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di\Service;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Reference
{
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function __toString()
    {
        return $this->service;
    }
}