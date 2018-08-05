<?php

namespace Modules\Authorization\Orm\Fields;

use Modules\Authorization\Forms\Fields\ParamsField as FormsParamsField;
use Modules\Authorization\Helpers\RouterHelper;
use Modules\Authorization\Orm\RouteParams;
use Phact\Orm\Fields\CharField;

class ParamsField extends CharField
{
	public $rawSet = false;

	public function setValue($value, $aliasConfig = null)
	{
		$value = $this->attributePrepareValue($value);

		return parent::setValue($value);
	}

	public function attributePrepareValue($value) {
		return new RouteParams($this->model->rt, $value);
	}

	public function dbPrepareValue($value)
	{
		return (string)$value;
	}

	public function setUpFormField($config = [])
	{
		if (!isset($config['class'])) {
			$config['class'] = FormsParamsField::class;
		}
		$config = parent::setUpFormField($config);

		return $config;
	}

}
