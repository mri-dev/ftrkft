<h1>Munkavállaló adatigénylés</h1>
<div class="subtitle">
  Az alábbi listában szerepelnek azok az igénylések, melyet a munkavállalók adtak le egy állásajánlatuk kapcsán munkavállalók személyes adatai iránt.
</div>
<div class="user-request">
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

        <div class="user request ads{$item.ID} gender{$u.user->getNeme('ID')}">
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
              </div>
            </div>
            <div class="status">
              <div class="date">
                {lang text="Felvéve"}: <strong>{$u.requested_at}</strong>
              </div>
              <div class="user-decide">
                {lang text="Felhasználó visszajelzés"}:
                <strong>
                  {if $u.feedback == '-1'}
                  <span class="inprogress">{lang text="Kapcsolatfelvétel alatt"}.</span>
                  {/if}
                  {if $u.feedback == '0'}
                  <span class="declined">{lang text="Kapcsolat felvéve: ajánlat nem érdekli"}.</span>
                  {/if}
                  {if $u.feedback == '1'}
                  <span class="accept">{lang text="Kapcsolat felvéve: ajánlat érdekli"}.</span>
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
<pre>{$requests->request_info|print_r}</pre>
