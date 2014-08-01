<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;

use DCP\Di\Definition\ClassDefinition;
use DCP\Di\Definition\FactoryDefinition;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class ServiceDefinition implements ServiceDefinitionInterface, ServiceDefinitionGettersInterface
{
    protected $type;
    protected $className;
    protected $friendlyName;

    public function __construct($className, $friendlyName = null)
    {
        $this->className = $className;
        $this->friendlyName = $friendlyName;
        $this->toClass();
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function getFriendlyName()
    {
        return $this->friendlyName;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function toClass($class = null)
    {
        $type = new ClassDefinition($class ?: $this->className);

        $this->type = $type;
        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function toFactory($callback)
    {
        $type = new FactoryDefinition($callback);
        $this->type = $type;
        return $type;
    }
}
