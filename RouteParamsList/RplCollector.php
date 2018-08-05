<?php

namespace Modules\Authorization\RouteParamsList;

use Phact\Main\Phact;

trait RplCollector
{

	protected static $rplFolder = 'RouteParamsList';
	protected static $rpls = [];

	private function getRplClasses()
	{
		$route = &$this->route;
		if (!isset(static::$rpls[$route->module]['routes'][$route->name])) {
			static::initRplRouteStruct();

			$module = Phact::app()->getModule($route->module);
			$classes = [];

			$modulePath = $module::getPath();
			$path = implode(DIRECTORY_SEPARATOR, [$modulePath, static::$rplFolder]);
			if (is_dir($path)) {
				foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $filename)
				{
					if ($filename->isFile()) {
						static::handleRplFile($filename);
					}
				}
			}

			static::replaceRpls();
		}

		return static::$rpls[$route->module]['routes'][$route->name];
	}

	private function initRplRouteStruct() {
		static::$rpls = array_replace_recursive(static::$rpls, [
			$this->route->module => [
				'lists' => [],
				'routes' => [
					$this->route->name => [],
				],
			]
		]);
	}

	private function handleRplFile($filename) {
		$route = &$this->route;
		$name = $filename->getBasename('.php');
		$rpl = implode('\\', ['Modules', $route->module, static::$rplFolder, $name]);
		$rpl = $rpl::o();

		if (in_array($rpl->paramName, $route->getParamsNames())) {
			if ($rpl->routes) {
				if (in_array($route->name, $rpl->routes)) {
					static::$rpls[$route->module]
						['routes']
						[$route->name]
						[$rpl->paramName] = $rpl::className();
				}
			} else {
				static::$rpls[$route->module]
					['lists']
					[$rpl->paramName] = $rpl::className();
			}
		}
	}

	private function replaceRpls() {
		$route = &$this->route;
		static::$rpls[$route->module]['routes'][$route->name] = array_replace_recursive(
			static::$rpls[$route->module]['lists'],
			static::$rpls[$route->module]['routes'][$route->name]
		);
	}
}
