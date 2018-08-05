<?php

namespace Modules\Authorization\Manage;

use Modules\Authorization\Forms\UserAdminForm;
use Modules\Authorization\Models\User;
use Modules\Manage\Contrib\Admin;
use Phact\Main\Phact;

class UserAdmin extends Admin
{
	public function getSearchColumns()
	{
		return ['name'];
	}

	public function getModel()
	{
		return new User();
	}

	public function getForm()
	{
		return new UserAdminForm();
	}

	public static function getName()
	{
		return 'Users';
	}

	public static function getItemName()
	{
		return 'User';
	}

	public function getAllUrl($parentId = null)
	{
		return Phact::app()->router->url('manage:roles');
	}
}
