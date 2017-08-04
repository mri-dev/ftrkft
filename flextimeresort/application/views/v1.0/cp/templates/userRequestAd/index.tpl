<h1 style="margin: 10px 0 5px 0;">Munkavállaló adatigénylés</h1>
<div class="subtitle">
  Az alábbi listában szerepelnek azok az igénylések, melyet a munkavállalók adtak le egy állásajánlatuk kapcsán munkavállalók személyes adatai iránt.
</div>

{if $requestError}
  <div class="alert alert-error">
    <i class="fa fa-exclamation-triangle"></i> {$requestError} {if $link_back_list} <a href="{$root}userRequestAd/?onlyunpicked=1">További kérelmek listázása >></a>{/if}
  </div>
{/if}

<div class="user-request">
  <div class="filters">
    <fieldset>
      <legend>Szűrők</legend>
      <ul>
        {assign var="filter_qry" value=http_build_query($filter_arr)}
        <li><a class="btn btn-sm btn-{if isset($smarty.get.ownpicked)}success{else}default{/if}" href="{$root}userRequestAd/?{array_query_toggler from=$filter_arr item='ownpicked'}">Felvett jelölések</a></li>
        <li><a class="btn btn-sm btn-{if isset($smarty.get.undown)}success{else}default{/if}" href="{$root}userRequestAd/?{array_query_toggler from=$filter_arr item='undown'}">Felvett, befejezettlen jelölések <i class="fa fa-retweet"></i></a></li>
        <li class="sep">|</li>
        <li class="hl hl-orange"><a class="btn btn-sm btn-{if isset($smarty.get.onlyunpicked)}success{else}default{/if}" href="{$root}userRequestAd/?{array_query_toggler from=$filter_arr item='onlyunpicked'}">Feldolgozatlan jelölések <i class="fa fa-question-circle-o"></i></a></li>
        <li class="hl hl-green"><a class="btn btn-sm btn-{if isset($smarty.get.onlyaccepted)}success{else}default{/if}" href="{$root}userRequestAd/?{array_query_toggler from=$filter_arr item='onlyaccepted'}">Pozitívan visszajelzet<i class="fa fa-check-circle"></i></a></li>
        <li class="hl hl-red"><a class="btn btn-sm btn-{if isset($smarty.get.onlydeclined)}success{else}default{/if}" href="{$root}userRequestAd/?{array_query_toggler from=$filter_arr item='onlydeclined'}">Negatívan visszajelzett <i class="fa fa-minus-circle"></i></a></li>
      </ul>
      <div class="divider lined"></div>
      <form class="" action="{$root}userRequestAd" method="get" id="filter_search">
        {if !empty($form_pre_filter)}
          {foreach from=$form_pre_filter item=item key=key}
            <input type="hidden" name="{$key}" value="{$item}">
          {/foreach}
        {/if}
        <div class="row">
          <div class="col-md-1">
            <label for="munkaado_id">Munkaadó ID</label>
            <input class="form-control" type="text" id="munkaado_id" name="requester_id" value="{$smarty.get.requester_id}">
          </div>
          <div class="col-md-1">
            <label for="munkavallalo_id">Munkavállaló ID</label>
            <input class="form-control" type="text" id="munkavallalo_id" name="tuid" value="{$smarty.get.tuid}">
          </div>
          <div class="col-md-4">
            <label for="kereses">Keresés &mdash; Név / Email</label>
            <input class="form-control" type="text" id="kereses" name="search" value="{$smarty.get.search}">
          </div>
          <div class="col-md-6">
            <button class="btn btn-default btn-sm" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
    </fieldset>
  </div>

  {if !empty($applied_filter)}
    <div class="applied-filters">
      {if isset($smarty.get.requester_id) && $applied_filter['requester']}
      <span><i class="fa fa-filter"></i> Szűrve: <strong>{$applied_filter['requester']->getName()}</strong> munkaadó általi igénylések</span>
      {/if}
      {if isset($smarty.get.tuid) && $applied_filter['target_user']}
      <span><i class="fa fa-filter"></i> Szűrve: <strong>{$applied_filter['target_user']->getName()}</strong> munkavállaló adatinak igénylései</span>
      {/if}
    </div>
  {/if}

  {if isset($smarty.get.hlad) || isset($smarty.get.requester_id)}
    <a href="{$root}userRequestAd" class="btn btn-default btn-sm backurl"> <i class="fa fa-long-arrow-left"></i> vissza a teljes listára</a>
  {/if}

  {if isset($smarty.get.setdecline)}
    <div class="request-action-panel panel-decline">
      <form class="" action="" method="post">
        <input type="hidden" name="requestAction" value="decline">
        <div class="info">
          <h2>A felhasználó NEGATÍV irányban jelzett vissza a kapcsolatfelvétel során?</h2>
          Negatív: a felhasználó elmondása szerint nem érdekli az ajánlat.
        </div>
        <div class="buttons">
          <a href="{$root}userRequestAd/?opened={$smarty.get.setdecline}&hlad={$smarty.get.hlad}" class="btn btn-danger">vissza</a>
          <button type="submit" name="yes" class="btn btn-success" value="1">Negatívan jelzett vissza! <i class="fa fa-times"></i></button>
        </div>
      </form>
    </div>
  {/if}

  {if isset($smarty.get.setallow)}
    <div class="request-action-panel panel-allower">
      <form class="" action="" method="post">
        <input type="hidden" name="requestAction" value="setallow">
        <div class="info">
          <h2>A felhasználó POZITÍV irányban jelzett vissza a kapcsolatfelvétel során?</h2>
          Pozitív: a felhasználó elmondása szerint érdekli az ajánlat.
        </div>
        <div class="buttons">
          <div class="options">
            <strong>Opcionális beállítások:</strong><br>
            <input type="checkbox" {if isset($smarty.get.grant_access) == '1'}checked="checked"{/if} name="access_granting" id="access_granting" value="1"> <label for="access_granting">Személyes kapcsolat adatok (telefonszám, e-mail cím, közösségi oldalak) hozzáférése a munkáltató részére a munkavállaló önéletrajzán.</label>
          </div>
          <a href="{$root}userRequestAd/?opened={$smarty.get.setallow}&hlad={$smarty.get.hlad}" class="btn btn-danger">vissza</a>
          <button type="submit" name="yes" class="btn btn-success" value="1">Pozitívan jelzett vissza <i class="fa fa-check-circle"></i></button>
        </div>
      </form>
    </div>
  {/if}

  {if isset($smarty.get.setgranted)}
    <div class="request-action-panel panel-allower">
      <form class="" action="" method="post">
        <input type="hidden" name="requestAction" value="setgranted">
        <div class="info">
          <h2>Munkavállaló önéletrajz személyes adatok (e-mail, telefonszám, közösségi oldalak) biztosítása a munkavállaló részére?</h2>
          A hozzáférés megadásától számított {$settings.USERREQUEST_ACCESS_GRANTED_DATEDIFF} napon keresztül érheti el a munkaadó a munkavállaló személyes adatait.
        </div>
        <div class="buttons">
          <a href="{$root}userRequestAd/?opened={$smarty.get.setallow}&hlad={$smarty.get.hlad}" class="btn btn-danger">vissza</a>
          <button type="submit" name="yes" class="btn btn-success" value="1">Hozzáférés biztosítása <i class="fa fa-check-circle"></i></button>
        </div>
      </form>
    </div>
  {/if}

  {if $requests->total_items != 0}

  <div class="item-status">
    <span class="total"><strong>{$requests->total_items} db</strong> adatigénylés</span> |
    {if $requests->infos.requests.finished != 0}
    <span class="finished">{$requests->infos.requests.finished} lezárt</span>
    {/if}

    {if $requests->infos.requests.untouched != 0}
    <span class="unaccepted">{$requests->infos.requests.untouched} kapcsolatfelvételre vár</span>
    {/if}

    {if $requests->infos.requests.accepted != 0}
    <span class="accepted">{$requests->infos.requests.accepted} pozitív visszajelzés</span>
    {/if}

    {if $requests->infos.requests.declined != 0}
    <span class="declined">{$requests->infos.requests.declined} negatív visszajelzés</span>
    {/if}
  </div>

  {while $requests->walk()}
    {assign var="item" value=$requests->get()}
    <div class="allas id{$item.ID}">
      <div class="header">
        <div class="author-info">
          {assign var="creator" value=$item.data->createdBy()}
          {assign var="author" value=$item.data->getAuthorData('author')}
          <span class="creator">Létrehozta: <strong>{$creator.name}</strong> <span class="by by-{$creator.by}">{$creator.by}</span></span>
          <span class="author">Hirdető: {if is_null($author->getName())}<em>- nincs hirdető adat -</em>{else}<strong>{$author->getName()}</strong>{/if}</span>
          <span class="id">#Felh.ID: <strong>{$author->getID()}</strong></span>
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
        <div class="info status-{if $requests->request_info[$item.ID].requests.untouched && $requests->request_info[$item.ID].requests.untouched != 0}hasnotaccept{else}allaccepted{/if}">
          {count($item.items)} megjelölt munkavállaló {if $requests->request_info[$item.ID].requests.untouched && $requests->request_info[$item.ID].requests.untouched != 0}/ <strong> Ebből {$requests->request_info[$item.ID].requests.untouched} db feldolgozásra várakozik!</strong>{/if} <a href="javascript:void(0)" onclick="openRequests({$item.ID})">Megnyitás</a>
        </div>
        {foreach from=$item.items item=u}
        <div class="user request ads{$item.ID} gender{$u.user->getNeme('ID')} status-{if $u.feedback == 1 && $u.access_granted == 1}allowed{elseif $u.feedback == 0}declined{elseif !is_null($u.admin_id)}unallowed{/if} {if $smarty.get.hlad == $item.ID && ($u.ID == $smarty.get.setallow || $u.ID == $smarty.get.pickrequest || $u.ID == $smarty.get.opened)}opened{/if}">
          <div class="status-ico">
            <i title="" class="fa {if $u.feedback == 1 && $u.access_granted == 1}fa-check-circle{elseif $u.feedback == 0}fa-minus-circle{elseif !is_null($u.admin_id)}fa-hourglass-half{elseif is_null($u.admin_id)}fa-question-circle-o{/if}"></i>
          </div>
          <div class="wrapper">
            <div class="profilimg">
              <img src="{$u.user->getProfilImg()}" alt="{$u.user->getName()}">
            </div>
            <div class="dataset">
              <div class="name">
                <a href="{$settings.page_url}{$u.user->getCVURL()}" target="_blank">{$u.user->getName()} <em>(#{$u.user->getID()})</em></a>
              </div>
              <div class="szakma">
                {$u.user->getAccountData('szakma_text')}
              </div>
              <div class="subline">
                <span class="city">{$u.user->getAccountData('lakcim_city')}</span>
                <span class="Email"><a href="mailto:{$u.user->getEmail()}"><i class="fa fa-globe"></i> {$u.user->getEmail()}</a></span>
                {if $u.user->getPhone()}
                <span class="Telefon"><a href="tel:{$u.user->getPhone()}"><i class="fa fa-phone"></i> {$u.user->getPhone()}</a></span>
                {else}
                <span class="Telefon"><i class="fa fa-phone"></i> Telefonszám hiányzik</span>
                {/if}
              </div>
            </div>
            <div class="status">
              <div class="actions">
                {if is_null($u.admin_id)}
                  <a href="{$root}userRequestAd/?pickrequest={$u.ID}&hlad={$item.ID}" class="btn btn-danger">Kezelés felvétele</a>
                {else}
                  {if $u.feedback != -1 || $u.access_granted == 1}
                  <span class="picker done"><strong>{$u.pick_admin_name}</strong> kezelte.
                  {else}
                    <span class="picker"><strong>{$u.pick_admin_name}</strong> jelenleg kezeli.
                  {/if}
                {/if}
              </div>

              <div class="date">
                Igény benyújtva: <strong>{$u.requested_at}</strong>
              </div>
              {if !is_null($u.admin_id)}
              <div class="user-decide">
                {lang text="Felhasználó visszajelzés"}:
                <strong>
                  {if $u.feedback == '-1'}
                  <span class="inprogress">Feldolgozatlan. Kapcsolatfelvételre vár.</span>
                  {/if}
                  {if $u.feedback == '0'}
                  <span class="declined">Kapcsolat felvéve: ajánlat nem érdekli.</span>
                  {/if}
                  {if $u.feedback == '1'}
                  <span class="accept">Kapcsolat felvéve: ajánlat érdekli.</span>
                  {/if}
                </strong>
                <div class="">
                  {if !is_null($u.admin_id) && $u.finished == 0}
                      {if $admin->getID() == $u.admin_id && $u.feedback == -1}
                        <a href="{$root}userRequestAd/?setdecline={$u.ID}&opened={$u.ID}&hlad={$item.ID}" class="btn btn-sm btn-danger">NEGATÍV: Nem érdekli</a>
                        <a href="{$root}userRequestAd/?setallow={$u.ID}&hlad={$item.ID}" class="btn  btn-sm btn-warning" style="color: white;">POZITÍV: Érdekli</a>
                        <a href="{$root}userRequestAd/?setallow={$u.ID}&grant_access=1&hlad={$item.ID}" class="btn  btn-sm btn-success">POZITÍV + Hozzéférés megadása</a>
                      {/if}
                  {/if}
                </div>
              </div>
              <div class="access-granted">
                {lang text="Teljes hozzáférés megadva"}:
                <strong>
                  {if $u.access_granted}
                  <span class="yes">{lang text="Igen"} ({$u.granted_date_at})</span>
                  {/if}
                  {if !$u.access_granted}
                  <span ng-if="!u.access_granted" class="no">{lang text="Nem"}</span>
                  {/if}
                </strong>
                {if !is_null($u.admin_id) && $u.feedback != -1}
                <div class="">
                  {if !is_null($u.admin_id) && $u.finished == 0}
                      {if $admin->getID() == $u.admin_id && $u.feedback == 1 && $u.access_granted == 0 }
                        <a href="{$root}userRequestAd/?setgranted={$u.ID}&opened={$u.ID}&hlad={$item.ID}" class="btn btn-sm btn-success">Hozzáférés megadása</a>
                      {/if}
                  {/if}
                </div>
                {/if}
              </div>
              {/if}
            </div>
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
    A keresési feltételek alapján nincsennek adatigénylés kérelmek.
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
