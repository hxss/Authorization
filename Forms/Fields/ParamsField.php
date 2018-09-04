<?php

namespace Modules\Authorization\Forms\Fields;

use Modules\Authorization\Orm\RouteParams;
use Phact\Form\Fields\CharField;
use Phact\Main\Phact;

class ParamsField extends CharField
{
	public $inputTemplate = 'forms/field/params/input.tpl';
	public $paramListTemplate = 'forms/field/params/input_list.tpl';

	public function getRenderValue() {
		return $this->applyParamsLists($this->getValue());
	}

	private function applyParamsLists($params = []) {
		$this->choices = $params instanceof RouteParams
			? $params->toList()
			: $params;

		return $params;
	}

	public function getParamsLists($param) {
		$params = $this->getRenderValue();
		$chParams = $this->getValue()->getChildParams($param);

		$lists = [];
		foreach ($chParams as $param) {
			$lists[$param] = $this->renderParamList($param, $params);
		}

		return $lists;
	}

	public function renderParamList($param, $params)
	{
		return $this->renderTemplate($this->paramListTemplate, [
			'id' => $this->getHtmlId(),
			'key' => $param,
			'name' => $this->getHtmlName(),
			'html' => $this->buildAttributesInput(),
			'params' => $params,
			'value' => $this->choices[$param],
		]);
	}
}
