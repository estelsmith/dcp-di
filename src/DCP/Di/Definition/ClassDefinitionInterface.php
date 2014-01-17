<?php

namespace DCP\Di\Definition;

interface ClassDefinitionInterface extends ShareableInterface
{
    /**
     * @param string $name
     * @param mixed $argument
     * @return $this
     */
    public function addArgument($name, $argument);

    /**
     * @param array $arguments
     * @return $this
     */
    public function addArguments(array $arguments);

    /**
     * @param string $method
     * @param array $arguments
     * @return $this
     */
    public function addMethodCall($method, array $arguments = []);

    /**
     * @param array $methods
     * @return $this
     */
    public function addMethodCalls(array $methods);
}
