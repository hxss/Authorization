<?php

namespace Modules\Authorization\Forms\Fields;

use Modules\Authorization\Models\Route;
use Phact\Form\Fields\DropDownField;

class RouteField extends DropDownField
{

	public function getValue()
	{
		if (!(int)$this->_value) {
			$route = new Route();
			$route->name = $this->_value;
			$route->label = $this->_value;
			$this->_value = $route->save();
		}

		return $this->_value;
	}

}
