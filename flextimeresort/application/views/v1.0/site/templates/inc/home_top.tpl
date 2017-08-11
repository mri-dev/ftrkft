{if !$hide_home_top}
<div class="header-searcher-container">
  {if !$hide_searcher}
  <div class="home-searcher">
    <div class="page-width">
      {if ($me && $me->logged() &&$me->isMunkaado())}
        {include file='inc/kereso_munkavallalok.tpl'}
      {else}
        {include file='inc/kereso.tpl'}
      {/if}

    </div>
  </div>
  {/if}
  {if !$hide_login_instruction}
  <div class="login-instruction">
    <div class="page-width">
      <div class="instuction">
        {include file='inc/login_instruction.tpl'}
      </div>
    </div>
  </div>
  {/if}
  {if !$hide_home_instruction}
  <div class="home-instruction hide-on-mobile">
    <div class="page-width">
      <div class="row">
        <div class="col-md-9">
          <div class="instuction">
            {include file='inc/home_instruction.tpl'}
          </div>
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
{/if}
