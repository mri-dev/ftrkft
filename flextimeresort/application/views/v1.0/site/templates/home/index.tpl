<div class="content-inside homepage">
  <div class="page-width">
    <div class="row">
      <div class="col-md-9">
        <div class="headerline">
          <h2>{lang text="Aktuális"}</h2>
          <div class="line"></div>
        </div>

        <div class="articles style-box-col3">
          {while $articles_top->walk()}
            <article class="">
              <div class="wrapper">
                <div class="img">
                  <a href="{$articles_top->URL()}"><img src="{$articles_top->Image()}" alt="{$articles_top->getTitle()}"></a>
                </div>
                <div class="title">
                  <h3><a href="{$articles_top->URL()}">{$articles_top->getTitle()}</a></h3>
                </div>
                <div class="desc">
                  {$articles_top->getSEODesc()}
                </div>
                <div class="nav">
                  <a href="{$articles_top->URL()}">{lang text="A teljes cikk megtekintése"}</a>
                </div>
              </div>
            </article>
          {/while}
        </div>

        <div class="headerline">
          <h2>{lang text="További cikkeink"}</h2>
          <div class="line"></div>
        </div>

        <div class="articles style-clear-col2">
          {while $articles_more->walk()}
            <article class="">
              <div class="wrapper">
                <div class="title">
                  <h3><a href="{$articles_more->URL()}">{$articles_more->getTitle()}</a></h3>
                </div>
                <div class="desc">
                  {$articles_more->getSEODesc()} <a class="read" href="{$articles_more->URL()}">{lang text="Tovább"}>></a>
                </div>
              </div>
            </article>
          {/while}
        </div>
      </div>
      <div class="col-md-3">
        {include file='inc/sidebar.tpl'}
      </div>
    </div>
  </div>
</div>
