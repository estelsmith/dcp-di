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

use ReflectionClass;
use ReflectionMethod;
use DCP\Di\Service\ServiceDefinitionInterface;
use DCP\Di\Service\ServiceDefinition;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class Container implements ContainerInterface {
	/**
	 * Array containing all service definitions for the container.
	 * @var array
	*/
	protected $_services = array();

	/**
	 * Array containing all instantiated shared services.
	 * @var array
	*/
	protected $_shared_services = array();

	/**
	 * Build all constructor parameters for a service, creating injected dependencies where needed.
	 * @param DCP\Di\Service\ServiceDefinitionInterface $service
	 * @param ReflectionMethod $constructor
	 * @param array $override_params
	 * @return array
	*/
	protected function _getServiceParams(ServiceDefinitionInterface $service, ReflectionMethod $constructor, $override_params = NULL) {
		$return_value = NULL;
		$constructor_params = $service->getParameters();
		$injected_params = array();

		if ($override_params) {
			$return_value = $override_params;
		} else {
			foreach ($constructor->getParameters() as $param) {
				$param_class = $param->getClass();

				if ($param_class) {
					$injected_params[] = $this->get($param_class->getName());
				}
			}

			$return_value = array_merge($injected_params, $constructor_params);
		}

		return $return_value;
	}

	/**
	 * Create an instance of a service based off the service definition.
	 * @param DCP\Di\Service\ServiceDefinitionInterface $service
	 * @param array $override_params
	 * @return mixed
	*/
	protected function _createInstance(ServiceDefinitionInterface $service, $override_params = NULL) {
		$return_value = NULL;
		$service_name = $service->getName();
		$class_name = $service->getClassName();

		if (class_exists($class_name)) {
			$class = new ReflectionClass($class_name);
			$constructor = $class->getConstructor();

			if (!($constructor && $constructor->isPublic() && $constructor->getNumberOfParameters() > 0)) {
				$return_value = $class->newInstance();
			} else {
				$params = $this->_getServiceParams($service, $constructor, $override_params);
				$return_value = $class->newInstanceArgs($params);
			}
		}

		return $return_value;
	}

	/**
	 * Register a service to be dependency-injected.
	 * @param string $service_name
	 * @return DCP\Di\ServiceDependencyInterface
	*/
	public function register($service_name) {
		$definition = new ServiceDefinition($service_name);

		$this->_services[$service_name] = $definition;

		return $definition;
	}

	/**
	 * Retrieve a service that has all dependencies injected.
	 * @param string $service_name
	 * @param array $override_params Inject these into the constructor rather than service definition parameters.
	 * @return mixed
	*/
	public function get($service_name, $override_params = NULL) {
		$return_value = NULL;
		$service = isset($this->_services[$service_name]) ? $this->_services[$service_name] : new ServiceDefinition($service_name, $service_name);
		$shared = $service->getIsShared();

		if ($shared && isset($this->_shared_services[$service_name])) {
			$return_value = $this->_shared_services[$service_name];
		} else {
			$return_value = $this->_createInstance($service, $override_params);

			if ($shared) {
				$this->_shared_services[$service_name] = $return_value;
			}
		}

		return $return_value;
	}
}