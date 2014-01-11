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
class ServiceDefinition implements ServiceDefinitionInterface
{
    protected $arguments = [];

    protected $methodCalls = [];

    protected $factory;

    protected $service = '';

    protected $serviceType = self::SERVICE_CLASS;

    protected $shared = false;

    /**
     * {@inheritdoc}
     */
    public function addArgument($name, $argument)
    {
        $this->arguments[$name] = $argument;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addArguments($arguments)
    {
        $this->arguments = array_merge($this->arguments, $arguments);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodCall($method, array $arguments = [])
    {
        $this->methodCalls[$method] = $arguments;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asShared()
    {
        $this->shared = true;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodCalls()
    {
        return $this->methodCalls;
    }

    /**
     * {@inheritdoc}
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * {@inheritdoc}
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * {@inheritdoc}
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function toClass($class)
    {
        $this->serviceType = self::SERVICE_CLASS;
        $this->service = $class;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toInstance($instance)
    {
        $this->serviceType = self::SERVICE_INSTANCE;
        $this->service = $instance;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toFactory($factory)
    {
        $this->factory = $factory;
        return $this;
    }
}