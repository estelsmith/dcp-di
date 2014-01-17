<?php

namespace DCP\Di\Definition;

class FactoryDefinition implements FactoryDefinitionInterface, FactoryDefinitionGettersInterface
{
    use ShareableTrait;

    protected $factory;

    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory()
    {
        return $this->factory;
    }
}