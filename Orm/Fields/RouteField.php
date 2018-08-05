<?php

namespace Modules\Authorization\Orm\Fields;

use Modules\Authorization\Forms\Fields\RouteField as FormsRouteField;
use Modules\Authorization\Helpers\RouterHelper;
use Modules\Authorization\Orm\Fields\CachedForeignField;
use Phact\Form\Fields\DropDownField;
use Phact\Orm\Fields\Field;

class RouteField extends CachedForeignField
{
	public static $routes = [];

	public function getRoutes() {
		if (!static::$routes) {
			$objNames = array_column($this->objects, 'name');
			$routes = array_keys(RouterHelper::getRoutes());
			$newRoutes = array_diff($routes, $objNames);
			static::$routes = array_combine($newRoutes, $newRoutes);
		}

		return static::$routes;
	}

	public function setUpFormField($config = [])
	{
		$config['class'] = FormsRouteField::class;
		$choices = [];
		if (!$this->getIsRequired()) {
			$choices[''] = '';
		}

		foreach ($this->objects as $object) {
			$choices[$object->pk] = (string) $object;
		}
		$choices += $this->routes;

		$config['choices'] = $choices;

		return Field::setUpFormField($config);
	}

}
