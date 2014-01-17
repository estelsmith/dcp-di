<?php

namespace DCP\Di\Definition;

interface ClassDefinitionGettersInterface extends ShareableGetterInterface
{
    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return string
     */
    public function getClass();

    /**
     * @return array
     */
    public function getMethodCalls();
}