<?php

namespace Modules\Authorization\TemplateLibraries;

use Phact\Main\Phact;
use Phact\Template\Renderer;
use Phact\Template\TemplateLibrary;

class CommonLibrary extends TemplateLibrary
{
	use Renderer;

	/**
	 * Check for user permissions
	 *
	 * @name hasPermission
	 * @kind accessorFunction
	 * @return string
	 */
	public static function hasPermission($routeName, $params = [])
	{
		return Phact::app()->user->hasPermission($routeName, $params);
	}
}
