<?php

namespace Modules\Authorization\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Authorization\Models\Permission;

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
