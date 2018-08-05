<?php

namespace Modules\Authorization\Manage;

use Modules\Authorization\Models\Route;
use Modules\Manage\Contrib\Admin;
use Phact\Main\Phact;

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

	public function getAllUrl($parentId = null)
	{
		return Phact::app()->router->url('manage:roles');
	}
}
