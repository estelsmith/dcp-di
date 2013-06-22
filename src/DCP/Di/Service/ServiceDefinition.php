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
class ServiceDefinition implements ServiceDefinitionInterface {
	/**
	 * The service name.
	 * @var string
	*/
	protected $_name;

	/**
	 * @var string
	*/
	protected $_class_name;

	/**
	 * @var boolean
	*/
	protected $_is_shared;

	/**
	 * Array containing the service definition's default constructor parameters.
	 * @var array
	*/
	protected $_parameters;

	/**
	 * @param string $name
	 * @param string class_name
	 * @param boolean $is_shared
	 * @param array $parameters
	*/
	public function __construct($name = NULL, $class_name = NULL, $is_shared = FALSE, $parameters = array()) {
		$this->_name = $name;
		$this->_class_name = $class_name;
		$this->_is_shared = $is_shared;
		$this->_parameters = $parameters;
	}

	/**
	 * Get the service's name.
	 * @return string
	*/
	public function getName() {
		return $this->_name;
	}

	/**
	 * Set the service's name.
	 * @param string $name
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * Get the service's class name.
	 * @return string
	*/
	public function getClassName() {
		return $this->_class_name;
	}

	/**
	 * Set the service's class name.
	 * @param string $class_name
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setClassName($class_name) {
		$this->_class_name = $class_name;
		return $this;
	}

	/**
	 * Check if the service is configured to be shared.
	 * @return boolean
	*/
	public function getIsShared() {
		return $this->_is_shared;
	}

	/**
	 * Set whether or not the service is supposed to be shared.
	 * @param boolean $shared
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setIsShared($is_shared) {
		$this->_is_shared = $is_shared;
		return $this;
	}

	/**
	 * Retrieve the constructor parameters for the service.
	 * @return array
	*/
	public function getParameters() {
		return $this->_parameters;
	}

	/**
	 * Set the constructor parameters for the service.
	 * @param array $parameters
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setParameters($parameters) {
		$this->_parameters = $parameters;
		return $this;
	}

	/**
	 * Configure class name, shared status, and parameters for the service definition.
	 * @param string $class_name
	 * @param boolean $shared
	 * @param array $params
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function asClass($class_name, $shared = FALSE, $params = array()) {
		$this->setClassName($class_name)
			->setIsShared($shared)
			->setParameters($params);

		return $this;
	}
}