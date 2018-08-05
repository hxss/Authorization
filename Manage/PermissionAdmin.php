<?php

namespace Modules\Authorization\Manage;

use Modules\Authorization\Forms\PermissionAdminForm;
use Modules\Authorization\Manage\PermissionParamAdmin;
use Modules\Authorization\Models\Permission;
use Modules\Manage\Contrib\Admin;

class PermissionAdmin extends Admin
{
	public static $ownerAttribute = 'group';

	public function getSearchColumns()
	{
		return [];
	}

	public function getModel()
	{
		return new Permission();
	}

	public static function getName()
	{
		return 'Group permissions';
	}

	public static function getItemName()
	{
		return 'Group permission';
	}
}
