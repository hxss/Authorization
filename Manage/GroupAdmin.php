<?php

namespace Modules\Authorization\Manage;

use Modules\Authorization\Manage\PermissionAdmin;
use Modules\Authorization\Models\Group;
use Modules\Manage\Contrib\Admin;
use Phact\Main\Phact;

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

	public static function getItemName()
	{
		return 'Group';
	}

	public function getRelatedAdmins()
	{
		return [
			'group_permissions' => PermissionAdmin::class
		];
	}

	public function getAllUrl($parentId = null)
	{
		return Phact::app()->router->url('manage:roles');
	}
}
