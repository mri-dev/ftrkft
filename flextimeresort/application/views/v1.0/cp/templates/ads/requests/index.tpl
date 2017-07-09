<h1>Állásajánlat jelentkezések</h1>
<div class="allas-requests">
  {while $requests->walk()}
  {assign var="item" value=$requests->get()}
  <div class="allas">
    <div class="header">
      <div class="author-info">
        {assign var="creator" value=$item.data->createdBy()}
        {assign var="author" value=$item.data->getAuthorData('author')}
        <span class="creator">Létrehozta: <strong>{$creator.name}</strong> <span class="by by-{$creator.by}">{$creator.by}</span></span>
        <span class="author">Hirdető: {if is_null($author->getName())}<em>- nincs hirdető adat -</em>{else}<strong>{$author->getName()}</strong>{/if}</span>
      </div>
      <div class="desc">
        {$item.data->shortDesc()}
      </div>
      <div class="ads-data">
        <span class="edit"><a target="_blank" href="{$root}ads/editor/{$item.ID}"><i class="fa fa-pencil"></i> szerkeszt</a></span>
        <span class="type">{$item.data->get('tipus_name')}</span> /
        <span class="cat">{$item.data->get('cat_name')}</span>
        <span class="city"><i class="fa fa-"></i> {$item.data->getCity()}</span>
      </div>
      <div class="contact">
        {if !empty($item.data->getAuthorData('phone'))}
        <span data-toggle="tooltip" data-placement="bottom" title="Megadott kapcsolat telefonszám"><i class="fa fa-phone"></i> {$item.data->getAuthorData('phone')}</span>
        {/if}
        {if !empty($item.data->getAuthorData('email'))}
        <span data-toggle="tooltip" data-placement="bottom" title="Megadott kapcsolati e-mail cím"><i class="fa fa-envelope"></i> {$item.data->getAuthorData('email')}</span>
        {/if}
        {if !empty($item.data->getAuthorData('name'))}
        <span data-toggle="tooltip" data-placement="bottom" title="Megadott hirdető neve"><i class="fa fa-address-card"></i> {$item.data->getAuthorData('name')}</span>
        {/if}
      </div>
    </div>
    <div class="requests">
      {foreach from=$item.items item=request}
        <div class="request status-{if $request.accepted == 1}allowed{else}unallowed{/if}">
          <div class="status">
            <i class="fa {if $request.accepted == 1}fa-check-circle{else}fa-question-circle-o{/if}"></i>
          </div>
          <div class="text">
            <strong>{$request.user->getName()}</strong> jelentkezett <strong>{$request.request_at}</strong> időponttal. {if $request.accepted == 1}<small>&mdash; <em><strong>{$request.admin_name}</strong> admin engedélyt adott ekkor: <strong>{$request.accepted_at}</strong>.</em></small>{/if}
          </div>
          <div class="contact">
            <div class="contact-info">
              {if !empty($request.user->getPhone())}
                <span data-toggle="tooltip" data-placement="bottom" title="Telefonszám"><i class="fa fa-phone"></i> {$request.user->getPhone()}</span>
              {/if}
              <span data-toggle="tooltip" data-placement="bottom" title="E-mail cím"><i class="fa fa-envelope"></i> {$request.user->getEmail()}</span>
            </div>
          </div>
        </div>
      {/foreach}
    </div>
  </div>
  {/while}
</div>