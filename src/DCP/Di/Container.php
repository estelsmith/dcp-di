<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di;
use DCP\Di\Definition\FactoryDefinition;
use DCP\Di\Definition\ClassDefinitionGettersInterface;
use DCP\Di\Exception\CannotResolveException;
use DCP\Di\Exception\NotFoundException;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Container implements ContainerInterface
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
     * {@inheritdoc}
     */
    public function get($id)
    {
        $service = null;

        $sharedInstances = $this->sharedInstances;
        $definition = $this->findServiceDefinition($id);

        if ($sharedInstances->contains($definition)) {
            $service = $sharedInstances->offsetGet($definition);
        } else {
            $definitionType = $definition->getType();

            $constructor = [$this, 'createClassInstance'];
            $arguments = [$definitionType];

            if ($definitionType instanceof FactoryDefinition) {
                $constructor = $definitionType->getFactory();
                $arguments = [$this];
            }

            $service = call_user_func_array($constructor, $arguments);

            if ($definitionType->isShared()) {
                $sharedInstances->offsetSet($definition, $service);
            }
        }

        if (!$service) {
            throw new NotFoundException();
        }

        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return !is_null($this->findServiceDefinition($name, false));
    }

    /**
     * {@inheritdoc}
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
    protected function createClassInstance(ClassDefinitionGettersInterface $definitionType)
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
     * @throws CannotResolveException
     */
    protected function getConstructorArguments($className, array $definitionArguments = [])
    {
        $arguments = [];
        $class = null;
        $constructor = null;

        if (class_exists($className)) {
            $class = new \ReflectionClass($className);
            $constructor = $class->getConstructor();
        }

        if ($constructor) {
            $parameters = $constructor->getParameters();

            foreach ($parameters as $parameter) {
                $parameterName = $parameter->getName();
                $argument = null;

                // Set the argument if it was provided by the service definition
                if (isset($definitionArguments[$parameterName])) {
                    $argument = $definitionArguments[$parameterName];
                } else {
                    $argumentSet = false;
                    $parameterClass = $parameter->getClass();

                    // Set the argument if it's instantiable or has a service definition in the container
                    if ($parameterClass) {
                        $isInstantiable = $parameterClass->isInstantiable();
                        $parameterClassName = $parameterClass->getName();

                        if ($isInstantiable || $this->has($parameterClassName)) {
                            $argument = new ServiceReference($parameterClassName);
                            $argumentSet = true;
                        }
                    }

                    // Set the argument if it hasn't already been set, and a default value exists
                    if (!$argumentSet && $parameter->isDefaultValueAvailable()) {
                        $argument = $parameter->getDefaultValue();
                        $argumentSet = true;
                    }

                    // Cry, because the container couldn't resolve the dependency.
                    if (!$argumentSet) {
                        throw new CannotResolveException(sprintf('The constructor for "%s" has a parameter "%s" that could not be resolved.', $className, $parameterName));
                    }
                }

                $arguments[] = $argument;
            }
        }

        return $arguments;
    }

    /**
     * Retrieves a service definition by name. Checks to see if there is a friendly service name defined, first.
     *
     * @param string $name
     * @param bool $makeDefault
     * @return ServiceDefinitionGettersInterface
     */
    protected function findServiceDefinition($name, $makeDefault = true)
    {
        $definition = null;

        if (isset($this->friendlyDefinitions[$name])) {
            $definition = $this->friendlyDefinitions[$name];
        } elseif (isset($this->definitions[$name])) {
            $definition = $this->definitions[$name];
        } elseif ($makeDefault) {
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
