<?php

namespace Modules\Authorization\Orm\Fields;

use Phact\Helpers\SmartProperties;
use Phact\Orm\Fields\ForeignField;

class CachedForeignField extends ForeignField
{
	use SmartProperties;

	private static $_objects = [];

	public function getObjects() {
		if (!isset(self::$_objects[static::class])) {
			$class = $this->getRelationModelClass();
			$objects = $class::objects()->all();
			self::$_objects[static::class] = array_combine(array_column($objects, 'id'), $objects);
		}

		return self::$_objects[static::class];
	}

	protected function fetchModel() {
		$value = $this->_attribute;

		return isset($this->objects[$value])
			? $this->objects[$value]
			: parent::fetchModel();
	}

}
