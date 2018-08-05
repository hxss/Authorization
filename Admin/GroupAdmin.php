<?php

namespace Modules\Authorization\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Authorization\Admin\PermissionAdmin;
use Modules\Authorization\Models\Group;

class GroupAdmin extends Admin
{
	public function getSearchColumns()
	{
		return ['name'];
	}

	public function getModel()
	{
		return new Group();
	}

	public static function getName()
	{
		return 'Groups';
	}

	public function getRelatedAdmins()
	{
		return [
			'group_permissions' => PermissionAdmin::class
		];
	}

	public static function getItemName()
	{
		return 'Group';
	}
}
