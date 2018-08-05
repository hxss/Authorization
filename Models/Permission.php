<?php

namespace Modules\Authorization\Models;

use Modules\Authorization\Helpers\RouterHelper;
use Modules\Authorization\Models\Group;
use Modules\Authorization\Models\Route;
use Modules\Authorization\Orm\Fields\ParamsField;
use Modules\Authorization\Orm\Fields\RouteField;
use Phact\Main\Phact;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\HasManyField;
use Phact\Orm\Model;

class Permission extends Model
{
	private $_route = null;

	public static function getFields()
	{
		return [
			'group' => [
				'class' => ForeignField::class,
				'modelClass' => Group::class
			],
			'route' => [
				'class' => RouteField::class,
				'modelClass' => Route::class,
				'label' => 'Route',
			],
			'params' => [
				'class' => ParamsField::class,
				'label' => 'Params',
				'null' => true,
			],
		];
	}

	public function ge(Permission $prmssn) {
		if ($this->rt->ge($prmssn->rt)) {
			if ($prmssn->params && $this->params) {
				if ($this->params->ge($prmssn->params)) {
					return true;
				}
			} else {
				return true;
			}
		}

		return false;
	}

	public function __toString()
	{
		return (string)$this->rt . " [{$this->params}]" ?? '';
	}

//	Fields
	public function setFieldValue($field, $value)
	{
		if ($this->getFieldsManager()->has($field))
			parent::setFieldValue($field, $value);
		else
			$this->__smartSet($field, $value);
	}

	public function setRt($route) {
		$this->_route = $route;
	}

	public function getRt() {
		if (!$this->_route) {
			$this->_route = $this->route;
		}

		return $this->_route;
	}
}
