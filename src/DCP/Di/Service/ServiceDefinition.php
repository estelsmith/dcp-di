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
	 * The bound service name.
	 * @var string
	*/
	protected $_binding;

	/**
	 * @var string|closure
	*/
	protected $_implementation;

	/**
	 * @var boolean
	*/
	protected $_is_singleton;

	/**
	 * Array containing the service definition's default constructor parameters.
	 * @var array
	*/
	protected $_arguments;

	/**
	 * @param string $binding
	 * @param string $implementation
	 * @param boolean $is_singleton
	 * @param array $arguments
	*/
	public function __construct($binding = NULL, $implementation = NULL, $is_singleton = FALSE, $arguments = array()) {
		$this->_binding = $binding;
		$this->_implementation = $implementation;
		$this->_is_singleton = $is_singleton;
		$this->_arguments = $arguments;
	}

	/**
	 * Get the bound service name.
	 * @return string
	*/
	public function getBinding() {
		return $this->_binding;
	}

	/**
	 * Set the service's name.
	 * @param string $binding
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setBinding($binding) {
		$this->_binding = $binding;
		return $this;
	}

	/**
	 * Get the bound service's implementation.
	 * @return string
	*/
	public function getImplementation() {
		return $this->_implementation;
	}

	/**
	 * Set the bound service's implementation.
	 * @param string $implementation
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setImplementation($implementation) {
		$this->_implementation = $implementation;
		return $this;
	}

	/**
	 * Set the bound service's implementation.
	 * @param string $implementation
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function to($implementation) {
		return $this->setImplementation($implementation);
	}

	/**
	 * Retrieve constructor parameters for the bound service.
	 * @return array
	*/
	public function getArguments() {
		return $this->_arguments;
	}

	/**
	 * Set constructor parameters for the bound service.
	 * @param array $arguments
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setArguments($arguments) {
		$this->_arguments = $arguments;
		return $this;
	}

	/**
	 * Get the bound service's singleton configuration.
	 * @return boolean
	*/
	public function getSingleton() {
		return $this->_is_singleton;
	}

	/**
	 * Set the bound service's singleton configuration.
	 * @param boolean $singleton
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setSingleton($singleton) {
		$this->_is_singleton = $singleton;
		return $this;
	}

	/**
	 * Configure service to be a singleton.
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function asSingleton() {
		return $this->setSingleton(TRUE);
	}
}