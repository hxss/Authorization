<?php

namespace Modules\Authorization\Commands;

use Modules\Authorization\Helpers\RouterHelper;
use Modules\Authorization\Models\Group;
use Modules\Authorization\Models\Route;
use Modules\Authorization\Models\User;
use Phact\Commands\Command;
use Phact\Main\Phact;

class InitCommand extends Command
{
	public $silent = false;

	public function handle($arguments = []) {
		$routes = $this->initRoutes();
		$groups = $this->initGroups($routes);
		$this->linkUsers($groups);
	}

	public function initRoutes() {
		echo "Init Routes:      ";

		$allRoutes = RouterHelper::getRoutes();
		$namespaces = array_unique(array_map(function($route) {
			return RouterHelper::getNamespace($route);
		}, array_keys($allRoutes)));
		sort($namespaces);

		$routes = [];
		foreach ($namespaces as $namespace) {
			$route = new Route();
			$route->name = "{$namespace}:*";
			$label = ucfirst($namespace);
			$route->label = "[{$label}:*]";
			$id = $route->save();

			if (!in_array($namespace, [
//				'admin',
				'manage',
			])) {
				$routes[Group::GUEST][] = $id;
			} else {
				$routes[Group::STAFF][] = $id;
			}
		}

//		$route = new Route();
//		$route->name = "admin:login";
//		$route->label = "Admin: login";
//		$routes[Group::GUEST][] = $route->save();

		$route = new Route();
		$route->name = "manage:login";
		$route->label = "Manage: login";
		$routes[Group::GUEST][] = $route->save();

		echo $this->color('Done!', 'green');
		echo PHP_EOL;

		return $routes;
	}

	public function initGroups($routes) {
		echo "Init Groups:      ";

		$admin = new Group();
		$admin->name = Group::ADMIN;
		$admin->save();

		$guest = new Group();
		$guest->name = Group::GUEST;
		$guest->routes = $routes[Group::GUEST];
		$guest->save();

		$staff = new Group();
		$staff->name = Group::STAFF;
		$staff->routes = $routes[Group::STAFF];
		$staff->save();

		echo $this->color('Done!', 'green');
		echo PHP_EOL;

		return [$admin, $guest, $staff];
	}

	public function linkUsers($groups) {
		[$admin, $guest, $staff] = $groups;
		echo "Link User->Group: ";

		$users = User::objects()->all();
		foreach ($users as $user) {
			$user->groups->link($guest);
			$user->groups->link($staff);

			if ($user->_is_superuser) {
				$user->groups->link($admin);
			}
		}

		echo $this->color('Done!', 'green');
		echo PHP_EOL;
	}
}
