
{foreach $value as $vkey => $title}
	<li class="premission__param__item">
		{set $selected = $vkey|in:$params[$key]}
		<input type="checkbox"
			id="{$id}-{$key}-{$vkey}"
			name="{$name}[{$key}][{$vkey}]"
			value="{$vkey}"
			{if $selected}checked="checked"{/if}
			{raw $html}
		>
		<label class="label" for="{$id}-{$key}-{$vkey}">{$title}</label>
	</li>
{/foreach}
