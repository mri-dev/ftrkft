<div class="allasok-view">
  <div class="header">
    <div class="page-width">
      <div class="row">
        <div class="col-md-9">
          <h1>{lang text="Állásajánlatok"}</h1>
          <div class="subtitle">
            {$allasok->total_items} {lang text="db"} {lang text="állás"}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-width">
    <div class="row">
      <div class="col-md-9">
        {include file='inc/allasok_list_top.tpl'}
        <div class="allasok style-new-home">
          {include file='inc/allasok.tpl'}
        </div>
        {include file='inc/allasok_list_bottom.tpl'}
      </div>
      <div class="col-md-3">
        <div class="sidebar">
          <div class="allasok style-history">
            {include file='inc/allasok_history.tpl'}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
