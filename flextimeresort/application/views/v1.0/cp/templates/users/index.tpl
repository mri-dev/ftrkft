<h1>Felhasználók</h1>
<div class="box">
	<h3>Felhasználók ({$lista.info.total_num})</h3>
	{$form->getMsg(1)}
	<div class="data">
		<table class="table datatable table-sm table-striped table-bordered nowrap">
			<thead>
				<tr>
					<th width="10">ID</th>
					<th>Elnevezés</th>
					<th width="150">E-mail / Login</th>
					<th width="80" class="center">Utoljára belépett</th>
					<th width="80" class="center">Regisztrált</th>
					<th width="60" class="center">Engedélyezve</th>
					<th width="60" class="center">Műveletek</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$lista.data item=user}
				<tr class="">
					<td><strong style="color:black;">{$user.ID}</strong></td>
					<td><strong>{$user.name}</strong></td>
					<td>{$user.email}</td>
					<td class="center" title="{$user.last_login_date}">{$user.last_login_date}</td>
					<td class="center" title="{$user.register_date}">{$user.register_date}</td>
					<td class="center">{if $user.engedelyezve}Engedélyezve{else}Nem{/if}</td>
					<td class="center">
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>

	</div>
</div>
