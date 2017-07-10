<h1>Állásajánlat jelentkezések</h1>
<div class="allas-requests">
  {while $requests->walk()}
  {assign var="item" value=$requests->get()}
  <div class="allas id{$item.ID}">
    <div class="header">
      <div class="author-info">
        {assign var="creator" value=$item.data->createdBy()}
        {assign var="author" value=$item.data->getAuthorData('author')}
        <span class="creator">Létrehozta: <strong>{$creator.name}</strong> <span class="by by-{$creator.by}">{$creator.by}</span></span>
        <span class="author">Hirdető: {if is_null($author->getName())}<em>- nincs hirdető adat -</em>{else}<strong>{$author->getName()}</strong>{/if}</span>
      </div>
      <div class="desc">
        <a title="Hirdetés adatlap" href="{$item.data->getURL()}" target="_blank">{$item.data->shortDesc()}</a>
      </div>
      <div class="ads-data">
        <span class="type">{$item.data->get('tipus_name')}</span> /
        <span class="cat">{$item.data->get('cat_name')}</span>
        <span class="city"><i class="fa fa-map-marker"></i> {$item.data->getCity()}</span>
        <span class="edit"><a target="_blank" href="{$root}ads/editor/{$item.ID}"><i class="fa fa-pencil"></i> szerkeszt</a></span>
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
      <div class="info status-{if $requests->request_info[$item.ID].requests.not_accepted && $requests->request_info[$item.ID].requests.not_accepted != 0}hasnotaccept{else}allaccepted{/if}">
        {count($item.items)} jelentkezés {if $requests->request_info[$item.ID].requests.not_accepted && $requests->request_info[$item.ID].requests.not_accepted != 0}/ <strong> Ebből {$requests->request_info[$item.ID].requests.not_accepted} db elfogadásra várakozik!</strong>{/if} <a href="javascript:void(0)" onclick="openRequests({$item.ID})">Megnyitás</a>
      </div>
      {foreach from=$item.items item=request}
        <div class="request ads{$item.ID} status-{if $request.accepted == 1}allowed{else}unallowed{/if} {if $smarty.get.hlad == $item.ID && $request.hashkey == $smarty.get.setallow}opened{/if}">
          <div class="status">
            <i class="fa {if $request.accepted == 1}fa-check-circle{elseif !is_null($request.admin_pick)}fa-hourglass-half{else}fa-question-circle-o{/if}"></i>
          </div>
          <div class="text">
            <strong>{$request.user->getName()}</strong> jelentkezett <strong>{$request.request_at}</strong> időponttal. {if $request.accepted == 1}<small>&mdash; <em><strong>{$request.admin_name}</strong> admin engedélyt adott ekkor: <strong>{$request.accepted_at}</strong>.</em></small>{/if}
          </div>
          <div class="actions">
            {if is_null($request.admin_pick)}
              <a href="{$root}ads/requests/?pickrequest={$request.hashkey}&hlad={$item.ID}" class="btn btn-danger">Kezelés felvétele</a>
            {else}
                <span class="picker"><strong>{$request.pick_admin_name}</strong> jelenleg kezeli.
                {if $admin->getID() == $request.admin_pick}
                  <a href="{$root}ads/requests/?setallow={$request.hashkey}&hlad={$item.ID}" class="btn btn-success">Hozzáférés engedélyezése</a>
                {/if}
            {/if}
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
{literal}
  <script type="text/javascript">
    function openRequests(adid) {
      $('.allas').addClass('defocus').removeClass('focus');
      $('.allas.id'+adid).removeClass('defocus').addClass('focus');
      $('.request.opened').removeClass('opened');
      $('.request.ads'+adid).addClass('opened');
    }
  </script>
{/literal}
