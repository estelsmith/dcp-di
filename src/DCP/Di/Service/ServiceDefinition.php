<?php

namespace DCP\Di\Service;

class ServiceDefinition implements ServiceDefinitionInterface {
	protected $_name;
	protected $_class_name;
	protected $_is_shared;
	protected $_parameters;

	public function __construct($name = NULL, $class_name = NULL, $is_shared = FALSE, $parameters = array()) {
		$this->_name = $name;
		$this->_class_name = $class_name;
		$this->_is_shared = $is_shared;
		$this->_parameters = $parameters;
	}

	public function getName() {
		return $this->_name;
	}

	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	public function getClassName() {
		return $this->_class_name;
	}

	public function setClassName($class_name) {
		$this->_class_name = $class_name;
		return $this;
	}

	public function getIsShared() {
		return $this->_is_shared;
	}

	public function setIsShared($is_shared) {
		$this->_is_shared = $is_shared;
		return $this;
	}

	public function getParameters() {
		return $this->_parameters;
	}

	public function setParameters($parameters) {
		$this->_parameters = $parameters;
		return $this;
	}

	public function asClass($class_name, $shared = FALSE, $constructor_params = array()) {
		$this->setClassName($class_name)
			->setIsShared($shared)
			->setParameters($constructor_params);

		return $this;
	}
}