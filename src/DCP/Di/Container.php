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
class Container
{
    /**
     * @var ServiceDefinition[]
     */
    protected $services = [];

    /**
     * @var ServiceDefinition[]
     */
    protected $friendlyServices = [];

    /**
     * Retrieve a configured service from the container.
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        $service = null;

        $definition = $this->findServiceDefinition($name);

        $serviceType = $definition ? $definition->getServiceType() : null;

        switch ($serviceType) {
            case ServiceDefinition::SERVICE_INSTANCE:
                $definitionService = $definition->getService();

                if ($definitionService instanceof ServiceReference) {
                    $service = $this->get((string)$definitionService);
                    $definition->setService($service);
                } else {
                    $service = $definition->getService();
                }
                break;

            case ServiceDefinition::SERVICE_CLASS:
                if (is_object($definition->getService())) {
                    $service = $definition->getService();
                } else {
                    $className = $definition->getService();
                    $arguments = $this->getConstructorArguments($className, $definition);
                    $service = $this->createInstance($className, $arguments, $definition->getMethodCalls());

                    if ($definition->isShared()) {
                        $definition->setService($service);
                    }
                }
                break;

            default:
                $arguments = $this->getConstructorArguments($name);
                $service = $this->createInstance($name, $arguments);
                break;
        }

        return $service;
    }

    /**
     * Register a service definition with the container.
     *
     * @param string $name
     * @param string $friendlyName
     * @return ServiceDefinition
     */
    public function register($name, $friendlyName = null)
    {
        $definition = new ServiceDefinition();
        $definition->setService($name);

        $this->services[$name] = $definition;

        if ($friendlyName) {
            $this->friendlyServices[$friendlyName] = $definition;
        }

        return $definition;
    }

    /**
     * Instantiates a class.
     *
     * @param string $className
     * @param mixed $arguments
     * @param mixed $methodCalls
     * @return mixed
     */
    protected function createInstance($className, $arguments = [], $methodCalls = [])
    {
        $finalArguments = [];

        foreach ($arguments as $argument) {
            $finalArguments[] = $argument instanceof ServiceReference ? $this->get((string)$argument) : $argument;
        }

        $class = new \ReflectionClass($className);

        $instance = $class->newInstanceArgs($finalArguments);

        foreach ($methodCalls as $method => $arguments) {
            $methodArguments = [];

            foreach ($arguments as $methodArgument) {
                $methodArguments[] = $methodArgument instanceof ServiceReference ? $this->get((string)$methodArgument) : $methodArgument;
            }

            call_user_func_array([$instance, $method], $methodArguments);
        }

        if ($instance instanceof ContainerAwareInterface) {
            call_user_func_array([$instance, 'setContainer'], [$this]);
        }

        return $instance;
    }

    /**
     * Resolves all constructor arguments for a given class.
     *
     * Checks for arguments in the following order:
     * 1. Explicitly defined argument in the corresponding service definition
     * 2. Service injection from the container
     * 3. Default value of the constructor argument
     *
     * @param string $className
     * @param ServiceDefinition $definition
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function getConstructorArguments($className, ServiceDefinition $definition = null)
    {
        $arguments = [];
        $definitionArguments = $definition ? $definition->getArguments() : [];

        $class = new \ReflectionClass($className);
        $constructor = $class->getConstructor();

        if ($constructor) {
            $parameters = $constructor->getParameters();

            foreach ($parameters as $parameter) {
                $parameterName = $parameter->getName();

                // Set the argument, if it was provided by the service definition.
                if (isset($definitionArguments[$parameterName])) {
                    $arguments[] = $definitionArguments[$parameterName];
                } else {
                    $parameterClass = $parameter->getClass();

                    if ($parameterClass) {
                        $arguments[] = new ServiceReference($parameterClass->getName());
                    } else {
                        if ($parameter->isDefaultValueAvailable()) {
                            $arguments[] = $parameter->getDefaultValue();
                        } else {
                            throw new \InvalidArgumentException(sprintf('The constructor for "%s" has a parameter "%s" that could not be resolved.', $className, $parameterName));
                        }
                    }
                }
            }
        }

        return $arguments;
    }

    /**
     * Retrieves a service definition by name. Checks to see if there is a friendly service name defined, first.
     *
     * @param string $name
     * @return ServiceDefinition
     */
    protected function findServiceDefinition($name)
    {
        $definition = null;

        if (isset($this->friendlyServices[$name])) {
            $definition = $this->friendlyServices[$name];
        } elseif (isset($this->services[$name])) {
            $definition = $this->services[$name];
        }

        return $definition;
    }
}