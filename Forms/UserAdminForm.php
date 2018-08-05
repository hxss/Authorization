<?php

namespace Modules\Authorization\Forms;

use Modules\Authorization\Models\User;
use Modules\User\Forms\UserAdminForm as UUserAdminForm;
use Phact\Form\Fields\CheckboxListField;

class UserAdminForm extends UUserAdminForm
{

	public function getModel()
	{
		return new User;
	}
}
