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

  {if $show_profil_flow}
  <div class="profil-instruction">
    <div class="page-width">
      <div class="row">
        <div class="col-md-9">
          <div class="instuction">
            {include file='inc/profil_instruction.tpl'}
          </div>
        </div>
        <div class="col-md-3"></div>
      </div>
    </div>
  </div>
  {/if}
</div>
{if $me && $me->isInaktiv()}
<div class="profil-inaktiv-alert">
  <div class="page-width">
    <div class="ico">
      <i class="fa fa-eye-slash"></i>
    </div>
    <div class="text">
      {lang text="Az Ön fiókja jelenleg inaktív. Így nem jelenik meg a listázásban."} <br>
      <a href="/ugyfelkapu/profil/alap">{lang text="Profil aktiválása"} <i class="fa fa-long-arrow-right"></i></a>
    </div>    
  </div>
</div>
{/if}
