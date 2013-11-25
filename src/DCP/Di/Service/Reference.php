<?php

namespace DCP\Di\Service;

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