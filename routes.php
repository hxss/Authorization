<?php

return [
	[
		'route' => '/params-list/{:pk}',
		'target' => [Modules\Authorization\Controllers\PermissionController::class, 'paramsList'],
		'name' => 'params_list'
	],
];
