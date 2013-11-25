<?php

namespace DCP\Di;

use DCP\Di\Service\Definition;
use DCP\Di\Service\Reference;

class Container
{
    protected $services = [];
    protected $friendlyServices = [];

    public function get($name)
    {
        $service = null;

        $definition = $this->getServiceDefinition($name);

        $serviceType = $definition ? $definition->getServiceType() : null;

        switch ($serviceType) {
            case Definition::SERVICE_INSTANCE:
                $definitionService = $definition->getService();

                if ($definitionService instanceof Reference) {
                    $service = $this->get((string)$definitionService);
                    $definition->setService($service);
                } else {
                    $service = $definition->getService();
                }
                break;

            case Definition::SERVICE_CLASS:
                $className = $definition->getService();
                $arguments = $this->getConstructorArguments($className, $definition);
                $service = $this->createService($className, $arguments, $definition->getMethodCalls());
                break;

            default:
                $arguments = $this->getConstructorArguments($name);
                $service = $this->createService($name, $arguments);
                break;
        }

        return $service;
    }

    public function register($name, $friendlyName = null)
    {
        $definition = new Definition();
        $definition->setService($name);

        $this->services[$name] = $definition;

        if ($friendlyName) {
            $this->friendlyServices[$friendlyName] = $definition;
        }

        return $definition;
    }

    protected function createService($className, $arguments = [], $methodCalls = [])
    {
        $finalArguments = [];

        foreach ($arguments as $argument) {
            $finalArguments[] = $argument instanceof Reference ? $this->get((string)$argument) : $argument;
        }

        $class = new \ReflectionClass($className);

        $instance = $class->newInstanceArgs($finalArguments);

        foreach ($methodCalls as $method => $arguments) {
            $methodArguments = [];

            foreach ($arguments as $methodArgument) {
                $methodArguments[] = $methodArgument instanceof Reference ? $this->get((string)$methodArgument) : $methodArgument;
            }

            call_user_func_array([$instance, $method], $methodArguments);
        }

        return $instance;
    }

    protected function getConstructorArguments($className, Definition $definition = null)
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
                    // Use the argument's default value, if it's available.
                    if ($parameter->isDefaultValueAvailable()) {
                        $arguments[] = $parameter->getDefaultValue();
                    } else {
                        $parameterClass = $parameter->getClass();

                        // Since there is no default value, try injecting from the container.
                        if ($parameterClass) {
                            $arguments[] = new Reference($parameterClass->getName());
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
     * @param string $name
     * @return Definition
     */
    protected function getServiceDefinition($name)
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