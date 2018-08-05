<?php

namespace Modules\Authorization\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Authorization\Models\Route;

class RouteAdmin extends Admin
{
	public function getSearchColumns()
	{
		return ['name'];
	}

	public function getModel()
	{
		return new Route();
	}

	public static function getName()
	{
		return 'Routes';
	}

	public static function getItemName()
	{
		return 'Route';
	}
}
