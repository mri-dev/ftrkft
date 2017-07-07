<div class="pull-left">
	<h1>Állásajánlatok</h1>
</div>
<div class="pull-right">
	<a href="{$root}ads/editor" class="btn btn-success"><i class="fa fa-plus-circle"></i> új hirdetés</a>
</div>
<div class="clearfix"></div>

<div class="box">
	<div class="">
		{if $filtered}
		<a class="remove-filter" href="{$root}ads/1">szűrőfeltételek eltávolítása <i class="fa fa-times"></i> </a>
		{/if}
	</div>
	<div class="page-infos">
		{$lista->current_page}. oldal / {$lista->total_pages}
	</div>
	{$form->getMsg(1)}
	<div class="data">
		{$pagination}
		<div class="row row-head">
			<div class="col-md-1 center">
				<strong>ID</strong>
			</div>
			<div class="col-md-4 center">
				<strong>Rövid ismertető</strong>
			</div>
			<div class="col-md-3 center">
				<strong>Típus / Kategória</strong>
			</div>
			<div class="col-md-2 center">
				<strong>Létrehozó</strong>
			</div>
			<div class="col-md-1 center">
				<strong>Közzététel</strong>
			</div>
			<div class="col-md-1 center">
				<i class="fa fa-gears"></i>
			</div>
		</div>
		<form action="{$root}ads" method="get">
		<div class="row row-search">
			<div class="col-md-1 center">
				<input type="text" name="ID" value="{$smarty.get.ID}" class="form-control form-control-sm">
			</div>
			<div class="col-md-4 center">
				<input type="text" placeholder="keresés..." name="s" value="{$smarty.get.s}" class="form-control form-control-sm">
			</div>
			<div class="col-md-3 center">
				<div class="row" style="padding: 0;">
					<div class="col-sm-6">
						<select class="form-control form-control-sm" name="meta[cat_type]">
							<option value="" selected="selected">Összes típus</option>
							{while $cat_tipus->walk()}
							<option value="{$cat_tipus->getID()}" {if $smarty.get.meta.cat_type == $cat_tipus->getID() }selected="selected"{/if}>{$cat_tipus->getName()}</option>
							{/while}
						</select>
					</div>
					<div class="col-sm-6">
						<select class="form-control form-control-sm" name="meta[cat_kategoria]">
							<option value="" selected="selected">Összes kategória</option>
							{while $cat_kategoria->walk()}
							<option value="{$cat_kategoria->getID()}" {if $smarty.get.meta.cat_kategoria == $cat_kategoria->getID() }selected="selected"{/if}>{$cat_kategoria->getName()}</option>
							{/while}
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-3 center">

			</div>
			<div class="col-md-1 center">
				<button type="submit" class="btn btn-primary form-control btn-sm"><i class="fa fa-search"></i></button>
			</div>
		</div>
		</form>
		{while $lista->walk()}
		<div class="row col-vertical-middle">
			<div class="col-md-1 center">
				{$lista->getID()}
			</div>
			<div class="col-md-4">
				{$lista->shortDesc()}
			</div>
			<div class="col-md-3 center">
				{$lista->get('tipus_name')} / {$lista->get('cat_name')}
			</div>
			<div class="col-md-2 center">
				{assign var="creator" value=$lista->createdBy()}
				<div>
					<strong>{$creator.name}</strong> ({$creator.by})
				</div>
				<em title="Létrehozás ideje">{$lista->createDate('Y. m. d. H:i')}</em>
			</div>
			<div class="col-md-1 center">
				{$lista->getPublishDate('Y. m. d. H:i')}
			</div>
			<div class="col-md-1 center actions">
				<a href="{$root}ads/editor/{$lista->getID()}" target="_blank"><i class="fa fa-pencil"></i></a>
				<a href="{$root}ads/remove/{$lista->getID()}"><i class="fa fa-trash"></i></a>
			</div>
		</div>
		{/while}
		{$pagination}
	</div>
</div>
