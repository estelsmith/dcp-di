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
interface ServiceDefinitionInterface {
	/**
	 * Get the bound service name.
	 * @return string
	*/
	public function getBinding();

	/**
	 * Set the service's name.
	 * @param string $binding
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setBinding($binding);

	/**
	 * Get the bound service's implementation.
	 * @return string
	*/
	public function getImplementation();

	/**
	 * Set the bound service's implementation.
	 * @param string $implementation
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setImplementation($implementation);

	/**
	 * Set the bound service's implementation.
	 * @param string $implementation
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function to($implementation);

	/**
	 * Retrieve constructor parameters for the bound service.
	 * @return array
	*/
	public function getArguments();

	/**
	 * Set constructor parameters for the bound service.
	 * @param array $arguments
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setArguments($arguments);

	/**
	 * Get the bound service's singleton configuration.
	 * @return boolean
	*/
	public function getSingleton();

	/**
	 * Set the bound service's singleton configuration.
	 * @param boolean $singleton
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function setSingleton($singleton);

	/**
	 * Configure service to be a singleton.
	 * @return DCP\Di\Service\ServiceDefinitionInterface
	*/
	public function asSingleton();
}