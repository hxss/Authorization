<?php

namespace Modules\Authorization;

use Modules\User\UserModule;
use Phact\Main\Phact;

class AuthorizationModule extends UserModule
{
	public static function onApplicationInit() {
		Phact::app()->event->on('application.beforeRunController', [
			Phact::app()->auth, 'checkAccess'
		]);
	}

	public static function getVerboseName() {
		return "Authorization";
	}
}
