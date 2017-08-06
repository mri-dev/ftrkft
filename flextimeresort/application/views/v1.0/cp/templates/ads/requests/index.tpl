<h1>Állásajánlat jelentkezések</h1>
<div class="allas-requests">
  <div class="filters">
    <fieldset>
      <legend>Szűrők</legend>
      <ul>
        {assign var="filter_qry" value=http_build_query($filter_arr)}
        <li><a class="btn btn-sm btn-{if isset($smarty.get.ownpicked)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='ownpicked'}">Felvett kérelmeim</a></li>
        <li><a class="btn btn-sm btn-{if isset($smarty.get.undown)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='undown'}">Felvett, befejezettlen kérelmek <i class="fa fa-retweet"></i></a></li>
        <li class="sep">|</li>
        <li class="hl hl-green"><a class="btn btn-sm btn-{if isset($smarty.get.onlyaccepted)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='onlyaccepted'}">Engedélyezett kérelmek <i class="fa fa-check-circle"></i></a></li>
        <li class="hl hl-orange"><a class="btn btn-sm btn-{if isset($smarty.get.onlyunpicked)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='onlyunpicked'}">Csak kezelésre váró <i class="fa fa-hourglass-half"></i></a></li>
        <li class="hl hl-red"><a class="btn btn-sm btn-{if isset($smarty.get.onlydeclined)}success{else}default{/if}" href="{$root}ads/requests/?{array_query_toggler from=$filter_arr item='onlydeclined'}">Elutasított kérelmek <i class="fa fa-minus-circle"></i></a></li>
      </ul>
    </fieldset>
  </div>
  {if $requestError}
    <div class="alert alert-error">
      <i class="fa fa-exclamation-triangle"></i> {$requestError} {if $link_back_list} <a href="{$root}ads/requests/?onlyunpicked=1">További kérelmek listázása >></a>{/if}
    </div>
  {/if}
  {if isset($smarty.get.createMessangerSession)}
  <div class="request-action-panel panel-allower">
    <form class="" action="" method="post">
      <input type="hidden" name="requestAction" value="createMessanger">
      <input type="hidden" name="user_id" value="{$smarty.get.requester_id}">
      <input type="hidden" name="allas_id" value="{$smarty.get.createMessangerSession}">
      <input type="hidden" name="admin_id" value="{$admin->getID()}">
      <div class="info">
        <h2>Új ügyfélkapus beszélgetés indítása.</h2>
        <p>A beszélgetés indítása gombra kattintva létrehozhat egy belső ügyfélkapus üzenetváltást a kérelmezővel.</p>
      </div>
      <div class="buttons">
        <div class="options">
          <div class="row">
            <div class="col-md-12">
              <label for="subject">Téma:</label>
              <input type="text" id="subject" value="Állásajánlat tájékoztató" name="subject" class="form-control" />
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="msg">Üzenet:</label>
              <textarea id="msg" name="msg" placeholder="Első üzenetem..." value="" class="form-control"></textarea>
            </div>
          </div>
        </div>
        <br>
        <strong>Az állásajánlat csatolva lesz a beszélgetéshez.</strong>
        <br><br>
        <a href="{$root}ads/requests/?opened={$smarty.get.setdecline}&hlad={$smarty.get.hlad}" class="btn btn-danger">MÉGSE <i class="fa fa-times"></i></a>
        <button type="submit" name="yes" class="btn btn-success" value="1">BESZÉLGETÉS INDÍTÁSA <i class="fa fa-comments-o"></i></button>
      </div>
    </form>
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
          </div>
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
    {if $requests->infos.requests.finished != 0}
    <span class="unaccepted">{$requests->infos.requests.not_finished} függőben</span>
    {/if}

    {if $requests->infos.requests.declined != 0}
    <span class="declined">{$requests->infos.requests.declined} elutasítva</span>
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
        <span class="author">Hirdető: {if is_null($author->getName())}<em>- nincs hirdető adat -</em>{else}<strong><a href="{$root}users/edit/{$author->getID()}" target="_blank">{$author->getName()}</a></strong>{/if}</span>
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
      <div class="info status-{if $requests->request_info[$item.ID].requests.in_progress && $requests->request_info[$item.ID].requests.in_progress != 0}hasnotaccept{else}allaccepted{/if}">
        {count($item.items)} jelentkezés {if $requests->request_info[$item.ID].requests.not_finished && $requests->request_info[$item.ID].requests.not_finished != 0}/ <strong> Ebből {$requests->request_info[$item.ID].requests.not_finished} db feldolgozásra várakozik!</strong>{/if} <a href="javascript:void(0)" onclick="openRequests({$item.ID})">Megnyitás</a>
      </div>
      {foreach from=$item.items item=request}
        <div class="request ads{$item.ID} status-{if $request.finished == 1 && $request.accepted == 1}allowed{elseif $request.finished == 1 && $request.declined == 1}declined{elseif !is_null($request.admin_pick)}unallowed{/if} {if $smarty.get.hlad == $item.ID && ($request.hashkey == $smarty.get.setallow || $request.hashkey == $smarty.get.pickrequest || $request.hashkey == $smarty.get.opened)}opened{/if}">
          <div class="status">
            <i title="{if $request.finished == 1 && $request.accepted == 1}Engedélyezve{elseif $request.finished == 1 && $request.declined == 1}Elutsítva{elseif !is_null($request.admin_pick)}Még nem feldolgozva{/if}" class="fa {if $request.finished == 1 && $request.accepted == 1}fa-check-circle{elseif $request.finished == 1 && $request.declined == 1}fa-minus-circle{elseif !is_null($request.admin_pick)}fa-hourglass-half{else}fa-question-circle-o{/if}"></i>
          </div>
          <div class="text">
            <strong><a href="{$root}users/edit/{$request.user->getID()}" target="_blank">{$request.user->getName()}</a></strong> jelentkezett <strong>{$request.request_at}</strong> időponttal. {if $request.accepted == 1}<small>&mdash; <em><strong>{$request.admin_name}</strong> admin engedélyt adott ekkor: <strong>{$request.accepted_at}</strong>.</em></small>{/if}
          </div>
          <div class="actions">
            {if is_null($request.admin_pick)}
              <a href="{$root}ads/requests/?pickrequest={$request.hashkey}&hlad={$item.ID}" class="btn btn-danger">Kezelés felvétele</a>
            {else}
              {if $request.finished == 1}
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
              {if !is_null($request.admin_pick) && $request.admin_pick == $admin->getID() && $request.declined != 1}
              <span class="messanger">
                {if $request.messanger}
                  <a class="session" href="{$root}messanger/session/{$request.messanger.sessionid}"><i class="fa fa-comments-o"></i> ügyfélkapus üzenetváltó </a>
                {else}
                  <a class="creating" href="{$root}ads/requests/?createMessangerSession={$item.ID}&opened={$request.hashkey}&hlad={$item.ID}&requester_id={$request.user_id}"><i class="fa fa-commenting"></i> beszélgetés indítása</a>
                {/if}
              </span>
            {/if}

            </div>
          </div>
          <div class="request-actions">
            {if !is_null($request.admin_pick) && $request.finished == 0}
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
