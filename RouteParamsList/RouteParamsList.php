<?php

namespace Modules\Authorization\RouteParamsList;

use Phact\Helpers\ClassNames;
use Phact\Helpers\SmartProperties;

abstract class RouteParamsList
{
	use ClassNames, SmartProperties;

	protected static $objects = [];

	protected $suffix = 'List';
	protected $paramName = '';

	public $routes = [];
	public $isCustomAvailable = false;

	protected $list = [];

	public static function o() {
		if (!isset(self::$objects[static::class]))
			self::$objects[static::class] = new static();

		return self::$objects[static::class];
	}

	public function getList() {
		if (!$this->list)
			$this->initList();

		return $this->list;
	}

	abstract public function initList();

	public function filter($params) {
		return $this->getList();
	}

	public function getParamName() {
		if (!$this->paramName) {
			$className = static::classNameShort();
			$this->paramName = strtolower(
				str_replace($this->suffix, '', $className)
			);
		}

		return $this->paramName;
	}
}
