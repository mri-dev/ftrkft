<div class="single-page">
  <div class="page-width">
    <div class="page-wrapper">
      {if $page->getId()}
        <h1 class="title">{$page->getTitle()}</h1>
        <div class="content-wrapper">
          {$page->getHtmlContent()}
        </div>
        {include file='inc/sharer.tpl'}
      {else}
        <div class="content-wrapper">
          <div class="page-not-found">
            <h1>{lang text="AZ_OLDAL_NEM_LETEZIK"}</h1>
            <div class="desc">{lang text="OLDAL_NOTFOUND_TEXT"}</div>
            <strong>{$settings.page_url|cat:$smarty.server.REQUEST_URI}</strong> <br>
            <a href="{$settings.page_url}">{lang text="FOOLDAL"}</a>
          </div>
        </div>
      {/if}
    </div>
  </div>
</div>
