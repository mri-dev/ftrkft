<div class="">
	<div class="">
		<h2 class="title">{$user.data.nev}</h2>
		{$form->getMsg(1)}
		{include file=$template_root|cat:"user/parts/menu.tpl"}
		<div class="page-menu-content">
			{if !$GETS[1]}
				{include file=$template_root|cat:"user/dashboard/index.tpl"}
			{else}
				{assign "filename" "/index.tpl"}
				{if $GETS[2]}
					{assign "filename" "/"|cat:$GETS[2]|cat:".tpl"}
				{/if}
				{include file=$template_root|cat:"user/"|cat:$GETS[1]|cat:$filename}
			{/if}	
		</div>

	</div>			
</div>