<?php

namespace Modules\Authorization\Controllers;

use Modules\Authorization\Admin\GroupAdmin;
use Modules\Authorization\Models\Permission;
use Modules\Manage\Forms\GroupModelForm;
use Phact\Controller\Controller;
use Phact\Form\ModelForm;
use Phact\Main\Phact;

class PermissionController extends Controller
{
	public function paramsList($pk) {
//		$this->request->validateCsrfToken();

		if ($this->request->getIsPost() && $this->request->getIsAjax()) {

			$param = $this->request->post->get('param');
			$model = Permission::objects()->filter(['pk' => $pk])->get();

			$formType = $this->request->post->has('ModelForm')
				? ModelForm::class
				: GroupModelForm::class;
			$form = new $formType;

			$form->setInstance($model);
			$form->setModel($model);

			$formData = [
				$formType::classNameShort() => $this->request
					->post->get($formType::classNameShort()),
			];

			if ($form->fill($formData)) {
				$attributes = $form->getAttributes();
				$form->setInstanceAttributes($attributes);
				$field = $form->getField('params');
				$field->setValue($model->params);
				$lists = $field->getParamsLists($param);

				$this->jsonResponse([
					'content' => $lists,
					'status' => 'success'
				]);
			}
		}
	}
}
