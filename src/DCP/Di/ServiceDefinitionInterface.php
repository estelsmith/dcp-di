<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;

use DCP\Di\Definition\ClassDefinitionInterface;
use DCP\Di\Definition\FactoryDefinitionInterface;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
interface ServiceDefinitionInterface
{
    /**
     * @param string $class
     * @return ClassDefinitionInterface
     */
    public function toClass($class = null);

    /**
     * @param callable $callback
     * @return FactoryDefinitionInterface
     */
    public function toFactory($callback);
}