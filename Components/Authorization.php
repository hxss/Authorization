<?php

namespace Modules\Authorization\Components;

use Modules\Authorization\Exceptions\AuthorizationException;
use Modules\Authorization\Models\User;
use Modules\User\Components\Auth;
use Phact\Main\Phact;

class Authorization extends Auth
{
	public $class = User::class;

	public $authorized = true;

	public function checkAccess($app, $controller, $action, $routeName, $routeParams) {
		$user = $this->getUser();
		if (!$user->is_superuser && !$user->hasPermission($routeName, $routeParams)) {
			$this->authorized = false;

			$this->log($controller, $action, $routeName, $routeParams);
			$this->alertNamespace($controller, $action, $routeName, $routeParams);
			$this->alert($controller, $action, $routeName, $routeParams);
			$this->log($controller, $action, $routeName, $routeParams, true);
			$this->accessDenied($controller, $action, $routeName, $routeParams);
		}
	}

	public function alertNamespace($controller, $action, $routeName, $routeParams) {
		$nmspce = Phact::app()->router->getCurrentNamespace();
		Phact::app()->event->trigger(
			"authorization.403.{$nmspce}",
			[$controller, $action, $routeName, $routeParams],
			$this,
			[$this, 'stopAlert']
		);
	}

	public function alert($controller, $action, $routeName, $routeParams) {
		if (!$this->authorized) {
			Phact::app()->event->trigger(
				"authorization.403",
				[$controller, $action, $routeName, $routeParams],
				$this,
				[$this, 'stopAlert']
			);
		}
	}

	public function accessDenied() {
		if (!$this->authorized) {
			http_response_code(403);die;
			throw new AuthorizationException("You do not have access to this page", 1);
		}
	}

	public function stopAlert() {
		$this->authorized = true;
	}

	public function log($controller, $action, $routeName, $routeParams, $error = false) {
		$log = [
			'user' => $this->getUser()->email,
			'routeName' => $routeName,
			'routeParams' => $routeParams,
			'controller' => $controller,
			'action' => $action,
		];
		$msg = "Authorization failed: " . print_r($log, true);

		if ($error)
			Phact::app()->logger->error($msg);
		else
			Phact::app()->logger->warning($msg);
	}
}
