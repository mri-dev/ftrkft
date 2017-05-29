<div class="header-searcher-container">
  {if !$hide_searcher}
  <div class="home-searcher">
    <div class="page-width">
      {include file='inc/kereso.tpl'}
    </div>
  </div>
  {/if}
  {if !$hide_home_instruction}
  <div class="home-instruction ugyfelkapu">
    <div class="page-width">
      <div class="row">
        <div class="col-md-9">
          <h1>{lang text="UGYFELKAPU"}{$subtitle}</h1>
        </div>
        <div class="col-md-3">
          <div class="login-holder">
            {include file='inc/login.tpl'}
          </div>
        </div>
      </div>
    </div>
  </div>
  {/if}
</div>
