<?php
/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
namespace DCP\Di\Exception;

use Interop\Container\Exception\NotFoundException as BaseException;

/**
 * @package dcp-di
 * @author Estel Smith <estel.smith@gmail.com>
 */
class NotFoundException extends \Exception implements ContainerException, BaseException
{

}
