<?php

namespace DCP\Di\Service;

interface ServiceDefinitionInterface {
	public function getName();
	public function setName($name);

	public function getClassName();
	public function setClassName($class_name);

	public function getIsShared();
	public function setIsShared($shared);

	public function getParameters();
	public function setParameters($parameters);

	public function asClass($class_name, $shared = FALSE, $constructor_params = array());
}