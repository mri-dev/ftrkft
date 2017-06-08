<div class="article-page">
  <div class="page-width">
    <div class="page-wrapper">
      {if $articles->getID()}
        <h1 class="title">{$articles->getTitle()}</h1>
        <div class="content-wrapper">
          {$articles->getHtmlContent()}
          <div class="time">
            <span data-toggle="tooltip" title="{lang text='A cikk közzétételének ideje.'}"><i class="fa fa-clock-o"></i> {$articles->getPublishAfter()|date_format:$settings.date_format}</span>  
          </div>
        </div>
        <div class="keywords">
          {foreach from=$articles->Keywords() item=keyword}
            <a href="{$settings.articles_list}?search={$keyword|trim}">{$keyword|trim}</a>
          {/foreach}
        </div>
        {assign var="seo_desc" value=$articles->getSEODesc()}
        {include file='inc/sharer.tpl'}
        <div class="more-articles">
          <div class="header">
            <h2>{lang text="További cikkek"}</h2>
          </div>

          <div class="articles style-box-col4">
            {while $more_articles->walk()}
              <article class="">
                <div class="wrapper">
                  <div class="img">
                    <a href="{$more_articles->URL()}"><img src="{$more_articles->Image()}" alt="{$more_articles->getTitle()}"></a>
                  </div>
                  <div class="title">
                    <h3><a href="{$more_articles->URL()}">{$more_articles->getTitle()}</a></h3>
                  </div>
                  <div class="desc">
                    {$more_articles->getSEODesc()}
                  </div>
                  <div class="nav">
                    <a href="{$more_articles->URL()}">{lang text="A teljes cikk megtekintése"}</a>
                  </div>
                </div>
              </article>
            {/while}
          </div>

        </div>
      {else}
        <div class="content-wrapper">
          <div class="page-not-found">
            <h1>{lang text="A cikk nem létezik."}</h1>
            <div class="desc">{lang text="CIKK_NOTFOUND_TEXT"}</div>
            <strong>{$settings.page_url|cat:$smarty.server.REQUEST_URI}</strong> <br>
            <form action="{$settings.articles_list}" method="get" style="margin: 15px auto; width: 50%;">
              <input type="text" class="form-control" name="search" value="" placeholder="{lang text='Keresés a cikkekben...'}">
            </form><br>
            <a href="{$settings.page_url}">{lang text="FOOLDAL"}</a>
          </div>
        </div>
      {/if}
    </div>
  </div>
</div>
