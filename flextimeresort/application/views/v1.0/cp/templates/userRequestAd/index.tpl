<h1>Munkavállaló adatigénylés</h1>
<div class="subtitle">
  Az alábbi listában szerepelnek azok az igénylések, melyet a munkavállalók adtak le egy állásajánlatuk kapcsán munkavállalók személyes adatai iránt.
</div>

{if $requestError}
  <div class="alert alert-error">
    <i class="fa fa-exclamation-triangle"></i> {$requestError} {if $link_back_list} <a href="{$root}userRequestAd/?onlyunpicked=1">További kérelmek listázása >></a>{/if}
  </div>
{/if}

<div class="user-request">
  {if isset($smarty.get.hlad)}
    <a href="{$root}userRequestAd" class="btn btn-default btn-sm backurl"> <i class="fa fa-long-arrow-left"></i> vissza a teljes listára</a>
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
                <a href="{$settings.page_url}{$u.user->getCVURL()}" target="_blank">{$u.user->getName()}</a>
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
              </div>
              {/if}
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
