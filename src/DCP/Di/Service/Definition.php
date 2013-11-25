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
namespace DCP\Di\Service;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Definition implements DefinitionInterface
{
    protected $arguments = [];

    protected $methodCalls = [];

    protected $service = '';

    protected $serviceType = self::SERVICE_CLASS;

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
    public function addMethodCall($method, $arguments)
    {
        $this->methodCalls[$method] = $arguments;

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
}