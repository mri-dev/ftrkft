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
        {if $access_granted}
        {else}
        <div class="content-text">
          {$allas->getPreContent()}
        </div>
        {/if}
      </div>
      <div class="info-side">
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
  {if !$access_granted && (($me && $me->logged() && $me->isUser()) || !$me->logged())}
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
          <div class="request-access">
            {if $me->logged()}
              <button class="btn btn-danger" type="button">{lang text="További információkat szeretnék"}. {lang text="Jelentkezés"}! <i class="fa fa-arrow-circle-right"></i></button>
            {else}
              <a href="/belepes?re={$smarty.server.REQUEST_URI}" class="btn btn-danger">{lang text="Bejelentkezés fiókjába"} <i class="fa fa-sign-in"></i></a>
            {/if}
          </div>
        </div>
      </div>
    </div>
  </div>
  {/if}
</div>
