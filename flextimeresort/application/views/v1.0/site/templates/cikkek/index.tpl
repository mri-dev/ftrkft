<div class="article-list">
  <div class="page-width">
    <div class="page-wrapper">
      <div class="row">
        <div class="col-md-9">
          <div class="list">
            <div class="header">
              <h1>{lang text="Cikkek"}</h1>
              {if !empty($smarty.get.search)}
                <div class="subtitle">
                  {lang text="Keresési találat alapján"}: <strong>{$smarty.get.search}</strong>
                </div>
              {/if}
            </div>
            <div class="articles style-list-col1">
              {while $articles->walk()}
                <article class="">
                  <div class="wrapper">
                    <div class="img">
                      <a href="{$articles->URL()}"><img src="{$articles->Image()}" alt="{$articles->getTitle()}"></a>
                    </div>
                    <div class="title">
                      <h3><a href="{$articles->URL()}">{$articles->getTitle()}</a></h3>
                    </div>
                    <div class="desc">
                      {$articles->getSEODesc()} &mdash; <em>{$articles->getPublishAfter()|date_format: $settings.date_format}</em>
                    </div>
                    <div class="nav">
                      <a href="{$articles->URL()}">{lang text="A teljes cikk megtekintése"}</a>
                    </div>
                  </div>
                </article>
              {/while}
              {$pagination}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <form class="" action="{$settings.articles_list}" method="get">
            <div class="sidebar-search">
              <div class="header">
                {lang text="Keresés"}:
              </div>
              <div class="input-group">
                <input type="text" name="search" value="{$smarty.get.search}" class="form-control">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
