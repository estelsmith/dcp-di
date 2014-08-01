<?php

namespace DCP\Di\Definition;

class ClassDefinition implements ClassDefinitionInterface, ClassDefinitionGettersInterface
{
    use ShareableTrait;

    protected $class;
    protected $arguments = [];
    protected $methodCalls = [];

    public function __construct($class)
    {
        $this->class = $class;
    }

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
    public function addArguments(array $arguments)
    {
        $this->arguments = array_merge($this->arguments, $arguments);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodCall($method, array $arguments = [])
    {
        $this->methodCalls[] = [$method, $arguments];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodCalls(array $methods)
    {
        $this->methodCalls = array_merge($this->methodCalls, $methods);
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
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodCalls()
    {
        return $this->methodCalls;
    }
}
