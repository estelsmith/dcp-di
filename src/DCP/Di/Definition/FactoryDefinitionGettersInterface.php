<?php

namespace DCP\Di\Definition;

interface FactoryDefinitionGettersInterface extends ShareableGetterInterface
{
    /**
     * @return callable
     */
    public function getFactory();
}
