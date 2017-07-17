<h1>Állásajánlat jelentkezések</h1>
<div class="allas-requests">
  <div class="filters">
    <fieldset>
      <legend>Szűrők</legend>
      <ul>
        {assign var="filter_qry" value=http_build_query($filter_arr)}
        <li><a class="btn btn-sm btn-{if isset($smarty.get.onlyunpicked)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='onlyunpicked'}">Csak kezelésre váró</a></li>
        <li><a class="btn btn-sm btn-{if isset($smarty.get.ownpicked)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='ownpicked'}">Felvett kérelmeim</a></li>
      </ul>
    </fieldset>
  </div>
  {if $requestError}
    <div class="alert alert-error">
      <i class="fa fa-exclamation-triangle"></i> {$requestError} <a href="{$root}ads/requests/?onlyunpicked=1">További kérelmek listázása >></a>
    </div>
  {/if}
  {if isset($smarty.get.setdecline)}
    <div class="request-action-panel panel-decline">
      <form class="" action="" method="post">
        <input type="hidden" name="requestAction" value="decline">
        <div class="info">
          <h2>Biztos, hogy elutasítja a(z) <strong>#{$smarty.get.setdecline}</strong> kérelmet?</h2>

        </div>
        <div class="buttons">
          <a href="{$root}ads/requests/?opened={$smarty.get.setdecline}&hlad={$smarty.get.hlad}" class="btn btn-danger">MÉGSE <i class="fa fa-times"></i></a>
          <button type="submit" name="yes" class="btn btn-success" value="1">IGEN, elutasítom! <i class="fa fa-trash"></i></button>
        </div>
      </form>
    </div>
  {/if}
  {if isset($smarty.get.setallow)}
    <div class="request-action-panel panel-allower">
      <form class="" action="" method="post">
        <input type="hidden" name="requestAction" value="setallow">
        <div class="info">
          <h2>Biztos, hogy engedélyt ad a(z) <strong>#{$smarty.get.setallow}</strong> kérelemhez?</h2>
          <p>
            Elfogadás során a kérelmező értesítve lesz e-mailben és az ügyfélértesítőn keresztül.
          </p>
        </div>
        <div class="buttons">
          <div class="options">
            <strong>Opcionális beállítások:</strong><br>
            <input type="checkbox" name="show_author_info" id="show_author_info" value="1"> <label for="show_author_info">Hirdető adatok közzététele a kérelmező részére</label>
          </p>
          <a href="{$root}ads/requests/?opened={$smarty.get.setallow}&hlad={$smarty.get.hlad}" class="btn btn-danger">MÉGSE <i class="fa fa-times"></i></a>
          <button type="submit" name="yes" class="btn btn-success" value="1">IGEN, engedélyt adok! <i class="fa fa-check-circle"></i></button>
        </div>
      </form>
    </div>
  {/if}
  {if $requests->total_items != 0}
  <div class="item-status">
    <span class="total"><strong>{$requests->total_items} db</strong> kérelem</span> |
    {if $requests->infos.requests.accepted != 0}
    <span class="accepted">{$requests->infos.requests.accepted} engedélyezve</span>
    {/if}
    {if $requests->infos.requests.not_accepted != 0}
    <span class="unaccepted">{$requests->infos.requests.not_accepted} függőben</span>
    {/if}
  </div>

  {if isset($smarty.get.hlad)}
    <a href="{$root}ads/requests" class="btn btn-default btn-sm backurl"> <i class="fa fa-long-arrow-left"></i> vissza a teljes listára</a>
  {/if}

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
        <a title="Hirdetés adatlap" href="{$item.data->getURL()}?atoken={$admin->getToken()}&showfull=1" target="_blank">{$item.data->shortDesc()}</a>
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
        <div class="request ads{$item.ID} status-{if $request.accepted == 1}allowed{else}unallowed{/if} {if $smarty.get.hlad == $item.ID && ($request.hashkey == $smarty.get.setallow || $request.hashkey == $smarty.get.pickrequest || $request.hashkey == $smarty.get.opened)}opened{/if}">
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
              {if !is_null($request.accepted_at) || !is_null($request.declined_at)}
              <span class="picker done"><strong>{$request.pick_admin_name}</strong> kezelte.
              {else}
                <span class="picker"><strong>{$request.pick_admin_name}</strong> jelenleg kezeli.
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
          <div class="request-actions">
            {if !is_null($request.admin_pick)}
                {if $admin->getID() == $request.admin_pick && $request.accepted == 0}
                  <a href="{$root}ads/requests/?setdecline={$request.hashkey}&opened={$request.hashkey}&hlad={$item.ID}" class="btn btn-danger">Kérelem elutasítása</a>
                  <a href="{$root}ads/requests/?setallow={$request.hashkey}&hlad={$item.ID}" class="btn btn-success">Hozzáférés engedélyezése</a>
                {/if}
            {/if}
          </div>
        </div>
      {/foreach}
    </div>
  </div>
  {/while}
  {else}
    <div class="no-result">
      <i class="fa fa-flag-checkered"></i>
      <h3>Nincs találat</h3>
      A keresési feltételek alapján nincsennek állásajánlat kérelmek.
    </div>
  {/if}
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
