<div class="allas-view">
  <div class="header">
    <div class="page-width">
      <h1>{$allas->shortDesc()}</h1>
      <div class="types">
        <div class="tipus" data-toggle="tooltip" data-placement="bottom" title="{lang text='Álláshirdetés típusa'}.">
          {$allas->get('tipus_name')}
        </div>
        <div class="cat" data-toggle="tooltip" data-placement="bottom" title="{lang text='Álláshirdetés kategória'}.">
          {$allas->get('cat_name')}
        </div>
      </div>
    </div>
  </div>
  <div class="page-width">
    <div class="allas-wrapper">
      <div class="content-side">
        {if $me->logged() && $me->getID() == $allas->getAuthorData('ID')}
          <h3>{lang text="Ez az Ön álláshirdetése"}:</h3>
        {/if}
        {if $access_granted}
        <div class="content-text">
          {$allas->getContent()}
        </div>
        {include file='inc/sharer.tpl'}
        {if $requested_data.show_author_info == 1 || $admin_access}
          {assign var="author_obj" value=$allas->getAuthorData('author')}
          {if !is_null($author_obj->getID())}
          <div class="author">
            <div class="img">
              <div class="imgwrapper">
                <img src="{$author_obj->getProfilImg()}" alt="{$allas->getAuthorData('name')}">
              </div>
            </div>
            <div class="data">
              <div class="name">
                {$allas->getAuthorData('name')}
              </div>
              <div class="phone">
                {$allas->getAuthorData('phone')}
              </div>
              <div class="email">
                {$allas->getAuthorData('email')}
              </div>
            </div>
          </div>
          {/if}
        {/if}
        {else}
        <div class="content-text">
          {$allas->getPreContent()}
        </div>
        {include file='inc/sharer.tpl'}
        {/if}
        <div class="show-on-print print-details">
          &mdash;&mdash;
          <div class="">
            {lang text="Ajánlat azonosító"}: <strong>#{$allas->getID()}</strong>
          </div>
          <div class="">
            {lang text="Hirdető azonosító"}: <strong>#{$allas->getAuthorData('ID')}</strong>
          </div>
          <div class="">
            {lang text="Ajánlat URL"}: <strong>{$settings.page_url}{$allas->getUrl()}</strong>
          </div>
        </div>
      </div>
      <div class="info-side">
        <div class="e print">
          <div class="">
            <a href="javascript:void(0);" onclick="window.print();"><i class="fa fa-print"></i> {lang text="Nyomtatás"}</a>
          </div>
        </div>
        <div class="e">
          <div class="title">{lang text="Megye / Város"}</div>
          <div class="val">
            {$allas->getMegye()}, {$allas->getCity()}
          </div>
        </div>
        <div class="e">
          <div class="title">{lang text="Munkakörök"}</div>
          <div class="val">
            <div class="term-value">
              {foreach from=$allas->getMetas('kulcs', 'munkakorok') item=munkakor}
              <a href="{$settings.allas_search_slug}?mk={$munkakor.value}">{$munkakor.value_text}</a>
              {/foreach}
            </div>
          </div>
        </div>
        {foreach from=$allas->getTerms() item=term}
        <div class="e term">
          <div class="title">{$term.title}</div>
          <div class="val">
            {foreach from=$term.data item=td}
            <div><span>{$td.name}</span></div>
            {/foreach}
          </div>
        </div>
        {/foreach}
        <div class="e">
          <div class="title">{lang text="Kulcsszavak"}</div>
          <div class="val">
            <div class="keywords">
              {foreach from=$allas->getKeywords() item=keyword}
              <a href="{$settings.allas_search_slug}?s={$keyword}">{$keyword}</a>
              {/foreach}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {if !$user_request_in_progress}
    {if $me->getID() != $allas->getAuthorData('ID')}
      {if !$access_granted && (($me && $me->logged() && $me->isUser()) || !$me->logged())}
      <div class="modal fade" id="accept-for-ad" tabindex="-1" role="dialog" aria-labelledby="accept-for-ad-label" aria-hidden="true">
        <div class="modal-dialog" role="document" ng-app="Ads" ng-controller="Request">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="accept-for-ad-label">{lang text="Jelentkezés megerősítése"}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              {lang text="A jelentkezés leadásával beérkezik hozzánk egy igény, melyet feldolgozva felvesszük Önnel a kapcsolatot."}
              <br><br>
              <div ng-show="inprogress">
                <div class="alert alert-warning align-center">
                  <i class="fa fa-spin fa-spinner"></i> {lang text="Jelentkezés folyamatban..."}
                </div>
              </div>
              <div ng-show="!not_requested">
                <div class="alert alert-success align-center">
                  <i class="fa fa-check-circle"></i> {lang text="Jelentkezését sikeresen leadta, hamarosan frissül az oldal."}
                </div>
              </div>
            </div>
            <div class="modal-footer" ng-show="!inprogress && not_requested">
              <button type="button" ng-show="!inprogress && not_requested" class="btn btn-secondary" data-dismiss="modal">{lang text="Bezárás"}</button>
              <button type="button" ng-show="!inprogress && not_requested" ng-click="requestAd({$allas->getID()})" class="btn btn-danger">{lang text="Jelentkezés az ajánlatra"}</button>
            </div>
          </div>
        </div>
      </div>
      <div class="accept-for-ad">
        <div class="page-width">
          <div class="view-design-fullcontent">
            <div class="ico">
              <i class="fa fa-lock"></i>
            </div>
            <div class="text">
              <div class="">
                <h4>{lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_TITLE"}</h4>
              </div>
              {lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_MSG"}
              <div class="infotext">
                {lang text="További információkat szeretnék_MSG"}
              </div>
              {if !$requested_ad}
              <div class="request-access">
                {if $me->logged()}
                  <button data-toggle="modal" data-target="#accept-for-ad" class="btn btn-danger" type="button">{lang text="További információkat szeretnék"}. {lang text="Jelentkezés"}! <i class="fa fa-arrow-circle-right"></i></button>
                {else}
                  <a href="/belepes?re={$smarty.server.REQUEST_URI}" class="btn btn-danger">{lang text="Bejelentkezés fiókjába"} <i class="fa fa-sign-in"></i></a>
                {/if}
              </div>
              {else}
                <div class="request-started">
                  <i class="fa fa-check-circle"></i>
                  {lang text="Ön jelentkezett erre a hirdetésre"}!
                </div>
              {/if}
            </div>
          </div>
        </div>
      </div>
      {else}
        <div class="access-granted">
          <div class="page-width">
            <div class="title">
              <i class="fa fa-check-circle"></i> {lang text='Hozzáférés engedélyezve'}
            </div>
            {if $admin_access}
              {lang text="Állás adatlap admin engedély"}
            {else}
              {lang text='Hozzáférés engedélyezve_TEXT' date=$requested_data.accepted_at admin=$requested_data.admin_name}
            {/if}

          </div>
        </div>
      {/if}
    {/if}
  {else}
  {if $user_request_in_progress.access_granted == '1'}
  <div class="access-granted">
    <div class="page-width">
      <div class="title">
        <i class="fa fa-check-circle"></i> {lang text='Hozzáférés engedélyezve'}
      </div>
      {if $admin_access}
        {lang text="Állás adatlap admin engedély"}
      {else}
        {lang text='Hozzáférés engedélyezve_TEXT' date=$user_request_in_progress.granted_date_at admin=$user_request_in_progress.admin_name}
      {/if}
    </div>
  </div>
  {elseif $user_request_in_progress.feedback == '-1'}
  <div class="accept-for-ad">
    <div class="page-width">
      <div class="view-design-fullcontent">
        <div class="ico">
          <i class="fa fa-lock"></i>
        </div>
        <div class="text">
          <div class="">
            <h4>{lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_TITLE"}</h4>
          </div>
          <div class="request-started">
            <i class="fa fa-check-circle"></i>
            <strong>{lang text="A munkáltató már érdeklődik Ön iránt!"}</strong><br>
            {lang text="Hamarosan felvesszük Önnel a kapcsolatot."}
          </div>
        </div>
      </div>
    </div>
  </div>
  {/if}

  {/if}
</div>
