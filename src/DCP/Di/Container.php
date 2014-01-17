<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;
use DCP\Di\Definition\FactoryDefinition;
use DCP\Di\Definition\ClassDefinitionGettersInterface;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Container
{
    /**
     * @var \SplObjectStorage
     */
    protected $sharedInstances;

    /**
     * @var ServiceDefinitionGettersInterface[]
     */
    protected $definitions = [];

    /**
     * @var ServiceDefinitionGettersInterface[]
     */
    protected $friendlyDefinitions = [];

    public function __construct()
    {
        $this->sharedInstances = new \SplObjectStorage();
    }

    /**
     * Retrieve a configured service from the container.
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        $service = null;

        $sharedInstances = $this->sharedInstances;
        $definition = $this->findServiceDefinition($name);

        if ($sharedInstances->contains($definition)) {
            $service = $sharedInstances->offsetGet($definition);
        } else {
            $definitionType = $definition->getType();
            $definitionTypeName = get_class($definitionType);

            $instantiator = [$this, 'createInstance'];

            if ($definitionTypeName === FactoryDefinition::class) {
                $instantiator = $definitionType->getFactory();
            }

            $service = call_user_func_array($instantiator, [$definitionType]);

            if ($definitionType->isShared()) {
                $sharedInstances->offsetSet($definition, $service);
            }
        }

        return $service;
    }

    /**
     * Register a service definition with the container.
     *
     * @param string $className
     * @param string $friendlyName
     * @return ServiceDefinitionInterface
     */
    public function register($className, $friendlyName = null)
    {
        $definition = new ServiceDefinition($className, $friendlyName);

        $this->definitions[$className] = $definition;

        if ($friendlyName) {
            $this->friendlyDefinitions[$friendlyName] = $definition;
        }

        return $definition;
    }

    /**
     * Instantiates a class.
     *
     * @param ClassDefinitionGettersInterface $definitionType
     * @return mixed
     */
    protected function createInstance(ClassDefinitionGettersInterface $definitionType)
    {
        $className = $definitionType->getClass();
        $arguments = $this->getConstructorArguments($className, $definitionType->getArguments());
        $methodCalls = $definitionType->getMethodCalls();
        $finalArguments = $this->hydrateServiceReferences($arguments);

        $instance = (new \ReflectionClass($className))->newInstanceArgs($finalArguments);

        foreach ($methodCalls as $methodCall) {
            list($method, $arguments) = $methodCall;
            $methodArguments = $this->hydrateServiceReferences($arguments);
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
     * @param array $definitionArguments
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function getConstructorArguments($className, array $definitionArguments = [])
    {
        $arguments = [];

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
     * @return ServiceDefinitionGettersInterface
     */
    protected function findServiceDefinition($name)
    {
        $definition = null;

        if (isset($this->friendlyDefinitions[$name])) {
            $definition = $this->friendlyDefinitions[$name];
        } elseif (isset($this->definitions[$name])) {
            $definition = $this->definitions[$name];
        } else {
            $definition = new ServiceDefinition($name);
        }

        return $definition;
    }

    /**
     * @param array $items
     * @return array
     */
    protected function hydrateServiceReferences(array $items)
    {
        $returnValue = [];

        foreach ($items as $item) {
            $returnValue[] = $item instanceof ServiceReference ? $this->get((string)$item) : $item;
        }

        return $returnValue;
    }
}
