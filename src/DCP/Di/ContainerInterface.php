<?php

namespace DCP\Di;

interface ContainerInterface {
	public function register($service_name);
	public function get($service_name, $override_params = NULL);
}