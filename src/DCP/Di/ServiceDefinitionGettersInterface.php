<?php

namespace DCP\Di;

interface ServiceDefinitionGettersInterface
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getFriendlyName();

    /**
     * @return mixed
     */
    public function getType();
}