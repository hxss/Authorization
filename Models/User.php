<?php

namespace Modules\Authorization\Models;

use Modules\Authorization\Helpers\RouterHelper;
use Modules\Authorization\Models\Route;
use Modules\ExtraFields\Orm\Fields\ManyToManyCheckboxField;
use Modules\User\Models\User as UUser;
use Phact\Main\Phact;
use Phact\Orm\Fields\BooleanField;
use Phact\Orm\Fields\CharField;
use Phact\Orm\Fields\EmailField;
use Phact\Orm\Fields\ForeignField;
use Phact\Orm\Fields\ManyToManyField;
use Phact\Orm\Model;

class User extends UUser
{
	protected $_is_superuser = null;

	protected $_groups = [];
	protected $_permissions = [];

	public static function getFields()
	{
		$fields = parent::getFields();
		return array_merge_recursive($fields, [
			'is_superuser' => [
				'editable' => false,
			],
			'is_staff' => [
				'editable' => false,
			],
			'groups' => [
				'class' => class_exists(ManyToManyCheckboxField::class)
					? ManyToManyCheckboxField::class
					: ManyToManyField::class,
				'label' => 'Groups',
				'modelClass' => Group::class,
			],
		]);
	}

	public static function getTableName()
	{
		return (new parent)->getTableName();
	}

	public function hasPermission($routeName, $params = null) {
//		return false;
//		return true;
		if ($this->is_superuser)
			return true;

		$route = new Route(['name' => $routeName]);
		$prmssn = new Permission([
			'rt' => $route,
			'params' => $params
		]);

		foreach ($this->permissions as $key => $permission)
			if ($permission->ge($prmssn)) {
				return true;
			}

		return false;
	}

	public function getPermissions() {
		if (!$this->_permissions) {
			$this->_permissions = Permission::objects()->filter([
				'group_id__in' => array_column($this->grps, 'id'),
			])->all();
		}

		return $this->_permissions;
	}

	public function getGrps() {
		if (!$this->_groups) {
			$groups = $this->groups->all();
			$this->_groups = array_combine(array_column($groups, 'name'), $groups);

			if (!isset($this->_groups[Group::GUEST]))
				$this->_groups[Group::GUEST] = Group::objects()->filter(['name' => Group::GUEST])->get();
		}

		return $this->_groups;
	}

	public function __toString()
	{
		return $this->email ?? '';
	}

//-----------------------------------------------------
// ### IS_SUPERUSER ###
//-----------------------------------------------------

	public function getFieldValue($field)
	{
		return $field == 'is_superuser'
			? $this->isSuperuser
			: parent::getFieldValue($field);
	}

	public function getIsSuperuser() {
		if (is_null($this->_is_superuser)) {
			$this->_is_superuser = in_array(Group::ADMIN, array_column($this->grps, 'name'));
		}

		return $this->_is_superuser;
	}

	public function get_is_superuser() {
		return parent::getFieldValue('is_superuser');
	}
}
