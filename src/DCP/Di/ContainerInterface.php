<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;

use Interop\Container\ContainerInterface as InteropContainerInterface;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
interface ContainerInterface extends InteropContainerInterface
{
    /**
     * Register a service definition with the container.
     *
     * @param string $className
     * @param string $friendlyName
     * @return ServiceDefinitionInterface
     */
    public function register($className, $friendlyName = null);
}
