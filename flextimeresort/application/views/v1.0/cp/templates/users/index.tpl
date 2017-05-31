<div class="pull-left">
	<h1>Felhasználók</h1>
</div>
<div class="pull-right">
	<a href="{$root}users/create" class="btn btn-success"><i class="fa fa-plus-circle"></i> új felhasználó</a>
</div>
<div class="clearfix"></div>
<div class="box">
	<h3>Felhasználók ({$lista.info.total_num})</h3>
	<div class="page-infos">
		{$lista.info.pages.current}. oldal / {$lista.info.pages.max}
	</div>
	{$form->getMsg(1)}
	<div class="data">
		{$pagination}
		<div class="row row-head">
			<div class="col-md-1 center">
				<strong>ID</strong>
			</div>
			<div class="col-md-4">
				<strong>Név / Email</strong>
			</div>

			<div class="col-md-1">
				<strong>Felh. csoport</strong>
			</div>
			<div class="col-md-1 center">
				<strong>Státusz</strong>
			</div>
			<div class="col-md-2 center">
				<strong>Utoljára belépett</strong>
			</div>
			<div class="col-md-2 center">
				<strong>Aktiválva</strong>
			</div>
			<div class="col-md-1 center">
				<i class="fa fa-gears"></i>
			</div>
		</div>
		<form action="{$root}users" method="get">
		<div class="row row-search">
			<div class="col-md-1 center">
				<input type="text" name="id" value="{$smarty.get.id}" class="form-control form-control-sm">
			</div>
			<div class="col-md-4">
				<input type="text" name="emailname" value="{$smarty.get.emailname}" class="form-control form-control-sm">
			</div>
			<div class="col-md-1 center">
				<select class="form-control form-control-sm" name="user_group">
					<option value="" {if $smarty.get.user_group == ''}selected="selected"{/if}>Összes</option>
					{foreach from=$usergroups item=ug key=id}
						<option value="{$id}" {if $smarty.get.user_group != '' && $smarty.get.user_group == $id}selected="selected"{/if}>{lang text=$ug}</option>
					{/foreach}
				</select>
			</div>
			<div class="col-md-1 center">
				<select class="form-control form-control-sm" name="engedelyezve">
					<option value="" {if $smarty.get.engedelyezve == ''}selected="selected"{/if}>Összes</option>
					<option value="0" {if $smarty.get.engedelyezve == '0'}selected="selected"{/if}>Tiltott</option>
					<option value="1" {if $smarty.get.engedelyezve == '1'}selected="selected"{/if}>Engedélyezve</option>
				</select>
			</div>
			<div class="col-md-2 center">

			</div>
			<div class="col-md-2 center">

			</div>
			<div class="col-md-1 center">
				<button type="submit" class="btn btn-primary form-control btn-sm"><i class="fa fa-search"></i></button>
			</div>
		</div>
		</form>
		{foreach from=$lista.data item=user}
		<div class="row col-vertical-middle">
			<div class="col-md-1 center">
				{$user.ID}
			</div>
			<div class="col-md-4">
				<div><strong>{$user.name}</strong></div>
				{$user.email}
			</div>
			<div class="col-md-1 center">
				{lang text=$usergroups[$user.user_group]}
			</div>
			<div class="col-md-1 center">
				{if $user.engedelyezve}Engedélyezve{else}Nem{/if}
			</div>
			<div class="col-md-2 center">
				{$user.last_login_date}
			</div>
			<div class="col-md-2 center">
				{$user.register_date}
			</div>
			<div class="col-md-1 center actions">
				<a href="{$root}users/edit/{$user.ID}" target="_blank"><i class="fa fa-pencil"></i></a>
				<a href="{$root}users/del/{$user.ID}" target="_blank"><i class="fa fa-trash"></i></a>
			</div>
		</div>
		{/foreach}
		{$pagination}
	</div>
</div>
