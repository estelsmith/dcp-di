<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
interface ContainerAwareInterface
{
    /**
     * Add a reference of the container to the service.
     *
     * @param Container $container
     */
    public function setContainer(Container $container);
}
