<?php
/* Copyright (c) 2013 Estel Smith
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;

use DCP\Di\Service\Definition;
use DCP\Di\Service\Reference;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Container
{
    /**
     * @var Definition[]
     */
    protected $services = [];

    /**
     * @var Definition[]
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

    /**
     * Register a service definition with the container.
     *
     * @param string $name
     * @param string $friendlyName
     * @return Definition
     */
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

    /**
     * Instantiates a class.
     *
     * @param string $className
     * @param mixed $arguments
     * @param mixed $methodCalls
     * @return mixed
     */
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

    /**
     * Resolves all constructor arguments for a given class.
     *
     * Checks for arguments in the following order:
     * 1. Explicitly defined argument in the corresponding service definition
     * 2. Default value of the constructor argument
     * 3. Possible service injection from the container
     *
     * @param string $className
     * @param Definition $definition
     * @return mixed
     * @throws \InvalidArgumentException
     */
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
     * Retrieves a service definition by name. Checks to see if there is a friendly service name defined, first.
     *
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