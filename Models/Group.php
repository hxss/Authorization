<?php

namespace Modules\Authorization\Models;

use Modules\Authorization\Models\Permission;
use Modules\Authorization\Models\Route;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\HasManyField;
use Phact\Orm\Fields\ManyToManyField;
use Phact\Orm\Model;

class Group extends Model
{
	public const ADMIN = 'admin';
	public const GUEST = 'guest';
	public const STAFF = 'staff';

	public static function getFields()
	{
		return [
			'name' => [
				'class' => CharField::class,
				'label' => 'Name',
			],
			'permissions' => [
				'class' => HasManyField::class,
				'modelClass' => Permission::class,
			],
			'routes' => [
				'class' => ManyToManyField::class,
				'modelClass' => Route::class,
				'through' => Permission::class,
			],
		];
	}

	public function __toString()
	{
		return $this->name ?? '';
	}

	public function __isset($name) {
		$manager = $this->getFieldsManager();
		return $manager->has($name);
	}
}
