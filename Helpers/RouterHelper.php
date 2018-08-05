<?php

namespace Modules\Authorization\Helpers;

use Phact\Main\Phact;

class RouterHelper {

	protected static $_routes = [];

	public static function getRoutes() {
		if (!static::$_routes) {
			$routes = [];
			foreach (Phact::app()->router->getRoutes() as $key => $route) {
				preg_match_all('`(\/|)\{.*?:(.+?)\}(\?|)`', $route[1], $matches, PREG_SET_ORDER);
				$params = [];
				foreach ($matches as $match) {
					$params[] = $match[2];
				}
				$routes[$route[3]] = [
					'url' => $route[1],
					'params' => $params,
				];
			}
			static::$_routes = $routes;
		}

		return static::$_routes;
	}

	public static function getNamespace($routeName) {
		return explode(':', $routeName)[0];
	}

	public static function getShortName($routeName) {
		return explode(':', $routeName)[1];
	}
}
