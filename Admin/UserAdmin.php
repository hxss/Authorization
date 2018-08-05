<?php

namespace Modules\Authorization\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Authorization\Forms\UserAdminForm;
use Modules\Authorization\Models\User;

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
}
