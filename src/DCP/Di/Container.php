<?php

namespace DCP\Di;

use ReflectionClass;
use DCP\Di\Service\ServiceDefinitionInterface;
use DCP\Di\Service\ServiceDefinition;

class Container implements ContainerInterface {
	protected $_services = array();
	protected $_shared_services = array();

	protected function _getServiceParams(ServiceDefinitionInterface $service, $constructor, $override_params) {
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

	protected function _createInstance(ServiceDefinitionInterface $service, $override_params) {
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

	public function register($service_name) {
		$definition = new ServiceDefinition($service_name);

		$this->_services[$service_name] = $definition;

		return $definition;
	}

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