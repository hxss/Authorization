
<div class="premission__param__list">
	<div class="premission__param__list__summary premission__param__item form-control">
		<label class="label" >*</label>
	</div>
	<ul class="checkbox-list form-control premission__param__list__content"
		data-param-name="{$key}"
	>
		<li class="premission__param__item">
			<input type="checkbox"
			id="{$id}-{$key}--all"
			data-all-toggle
			{raw $html}
			>
			<label class="label" for="{$id}-{$key}--all">Select/Unselect all</label>
		</li>
		{include 'forms/field/params/input_list.tpl'}
	</ul>
</div>
