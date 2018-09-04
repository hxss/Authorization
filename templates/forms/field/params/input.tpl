
{if ($value is \Modules\Authorization\Orm\RouteParams) && $value->isArray()}
	{* {$.dependency_css('Authorization/params/params.css', 'modules')}
	{$.dependency_js('Authorization/params/paramsSet.js', 'modules')}
	{$.dependency_js('Authorization/params/params.js', 'modules')} *}
	<link rel="stylesheet" href="/static_modules/Authorization/params/params.css"/>
	<script type="text/javascript" src="/static_modules/Authorization/params/paramsSet.js"></script>
	<script type="text/javascript" src="/static_modules/Authorization/params/params.js"></script>

	{set $params = $value}
	{set $permissionId = $field->form->model->id}
	<div class="premission__params_list">
		{foreach $field->choices as $key => $value}
			<div class="premission__param">
				<label class="label premission__param__label" for="{$id}-{$key}">{$key}</label>
				{if $value is array}
					{include 'forms/field/params/input_selector.tpl'}
				{else}
					{include 'forms/field/params/input_text.tpl'}
				{/if}
			</div>
			{if !$value@last}
				<p class="permission__slash">/</p>
			{/if}
		{/foreach}
	</div>

	{* {inline_js}
		<script>
			$('.premission__params_list').paramsSet({
				url: '{url 'authorization:params_list' $permissionId}'
			});
		</script>
	{/inline_js} *}
	<script>
		$('.premission__params_list').paramsSet({
			url: '/authorization/params-list/60'
		});
	</script>
{else}
	{include 'forms/field/default/input.tpl'}
{/if}
