<?php

namespace Modules\Authorization\Models;

use Modules\Authorization\Helpers\RouterHelper;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Model;

class Route extends Model
{
	protected $_namespace  = '';
	protected $_shortName = '';
	protected $_paramsNames = [];

	public static function getFields()
	{
		return [
			'label' => [
				'class' => CharField::class,
				'label' => 'Label',
			],
			'name' => [
				'class' => CharField::class,
				'label' => 'Name',
			],
		];
	}

	public function ge(Route $route) {
		return $this->eq($route)
			|| $this->gt($route);
	}

	public function eq(Route $route) {
		return $this->name == $route->name;
	}

	public function gt(Route $route) {
		return $this->shortName == '*'
			&& $this->namespace == $route->namespace;
	}

	public function getModule() {
		return ucfirst($this->getNamespace());
	}

	public function getNamespace() {
		if (!$this->_namespace) {
			$this->_namespace = RouterHelper::getNamespace($this->name);
		}

		return $this->_namespace;
	}

	public function getShortName() {
		if (!$this->_shortName) {
			$this->_shortName = RouterHelper::getShortName($this->name);
		}

		return $this->_shortName;
	}

	public function getParamsNames() {
		if (!$this->_paramsNames) {
			$routes = RouterHelper::getRoutes();
			$this->_paramsNames = isset($routes[$this->name])
				? $routes[$this->name]['params']
				: [];
		}

		return $this->_paramsNames;
	}

	public function __toString()
	{
		return $this->label ?? $this->name ?? '';
	}

	public function __isset($name) {
		$manager = $this->getFieldsManager();
		return $manager->has($name);
	}
}
