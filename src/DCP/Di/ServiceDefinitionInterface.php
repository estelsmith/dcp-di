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
interface ServiceDefinitionInterface
{
    const SERVICE_CLASS = 'class';
    const SERVICE_INSTANCE = 'instance';

    /**
     * Adds a constructor argument by name.
     *
     * @param string $name
     * @param mixed $argument
     * @return $this
     */
    public function addArgument($name, $argument);

    /**
     * Adds a list of constructor arguments by name.
     *
     * @param mixed $arguments
     * @return $this
     */
    public function addArguments($arguments);

    /**
     * Configure the service to call a method with given arguments after being constructed.
     *
     * @param string $method
     * @param array $arguments
     * @return $this
     */
    public function addMethodCall($method, array $arguments = []);

    /**
     * Retrieve the list of constructor arguments.
     *
     * @return mixed
     */
    public function getArguments();

    /**
     * Retrieve the list of method calls to be made once the service is initialized.
     *
     * @return mixed
     */
    public function getMethodCalls();

    /**
     * Returns the service. This will either be a class name to be constructed, or an object instance.
     *
     * @return mixed
     */
    public function getService();

    /**
     * Returns the service type that was registered.
     *
     * @return string
     */
    public function getServiceType();

    /**
     * Sets the list of constructor arguments by name.
     *
     * @param mixed $arguments
     * @return $this
     */
    public function setArguments($arguments);

    /**
     * Set the service. This is either a class name or an object instance, depending on the service type.
     *
     * @param mixed $service
     * @return $this
     */
    public function setService($service);

    /**
     * Sets the service to be a new instance of the specified class.
     *
     * @param string $class
     * @return $this
     */
    public function toClass($class);

    /**
     * Sets the services to be the instance given. Multiple requests for the service will return the same instance.
     *
     * @param mixed $instance
     * @return $this
     */
    public function toInstance($instance);
}