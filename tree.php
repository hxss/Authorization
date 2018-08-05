<?php
return [
	'pages' => [
		[
			'admin' => \Modules\Authorization\Manage\UserAdmin::class
		],
		[
			'admin' => \Modules\Authorization\Manage\GroupAdmin::class
		],
		[
			'admin' => \Modules\Authorization\Manage\RouteAdmin::class
		],
	]
];
