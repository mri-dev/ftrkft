<div class="login-container {if $me && $me->logged()}as-logged{/if}">
  {if $me && $me->logged()}
  <nav>
    <ul>
      <li class="header"><span class="ico"><i class="fa fa-gears"></i></span> {lang text="SAJAT_PROFIL"}</li>
      <li class="{if $smarty.get.tag == 'user/ugyfelkapu' && $smarty.get.p == ''}active{/if}"><a href="/ugyfelkapu/"><span class="ico"><i class="fa fa-commenting-o"></i></span> {lang text="ERTESITO_KOSZPONT"} {if $unwatched_alerts && $unwatched_alerts != 0}
        <span class="badge">{$unwatched_alerts}</span>
      {/if}</a></li>
      {if $me && $me->isUser()}
      <li class="{if $smarty.get.tag == 'user/ugyfelkapu' && $smarty.get.p == 'apps'}active{/if}"><a href="/ugyfelkapu/apps"><span class="ico"><i class="fa fa-handshake-o"></i></span> {lang text="Jelentkezéseim"}</a></li>
      {/if}
      <li class="{if $smarty.get.tag == 'user/ugyfelkapu' && $smarty.get.p == 'beallitasok'}active{/if}"><a href="/ugyfelkapu/beallitasok"><span class="ico"><i class="fa fa-gear"></i></span> {lang text="BEALLITASOK"}</a></li>
      <li class="{if $smarty.get.tag == 'user/ugyfelkapu' && $smarty.get.p == 'profil'}active{/if}"><a href="/ugyfelkapu/profil"><span class="ico"><i class="fa fa-pencil"></i></span> {lang text="PROFIL_SZERKESZTES"}</a></li>
      {if $me && $me->isUser()}
      <li class="cv-link"><a target="_blank" href="{$me->getCVUrl()}"><span class="ico"><i class="fa fa-file-text"></i></span> {lang text="ONLINE_ONELETRAJZOM"}</a></li>
      {/if}
      {if $me && $me->isMunkaado()}
      <li class="{if $smarty.get.tag == 'user/ugyfelkapu' && $smarty.get.p == 'hirdetesek'}active{/if}"><a href="/ugyfelkapu/hirdetesek"><span class="ico"><i class="fa fa-file-text"></i></span> {lang text="Hirdetések"}</a></li>
      {/if}
      <li class="{if $smarty.get.tag == 'user/ugyfelkapu' && $smarty.get.p == 'uzenetek'}active{/if}"><a href="/ugyfelkapu/uzenetek/inbox"><span class="ico"><i class="fa fa-envelope-square"></i></span> {lang text="UZENETEK_BEERKEZETT"} {if $messangerinfo.total_unreaded != 0}
        <span class="badge">{$messangerinfo.total_unreaded}</span>
      {/if}</a></li>
      <li class="logout"><a href="/ugyfelkapu/?logout=1">{lang text="KIJELENTKEZES"} <i class="fa fa-sign-out"></i></a></li>
    </ul>
  </nav>
  {else}
  {assign var="remembermehash" value=\Hash::loadRememberMeHash()}
  <form class="" action="/forms/auth" method="post">
    <input type="hidden" name="return" value="/belepes">
    <input type="hidden" name="form" value="1">
    <input type="hidden" name="session_path" value="/user/belepes">
    <div class="form-holder line">
      <img src="{$smarty.const.IMG}icons/circle/form-user.svg" alt="{lang text='EMAIL_ADDRESS'}">
      <input type="text" name="email" class="form-control" id="email" placeholder="{lang text='EMAIL_ADDRESS'}" value="{if $remembermehash}{$remembermehash.email}{/if}">
    </div>
    <div class="form-holder line">
      <img src="{$smarty.const.IMG}icons/circle/form-lock.svg" alt="">
      <input type="password" id="password" name="password" class="form-control" value="{if $remembermehash}{$remembermehash.password_hash}{/if}">
    </div>
    <div class="actions">
      <div class="whattodo line">
        <div class="row">
          <div class="col-md-6">
            <input type="checkbox" class="ccb" id="login_remember" {if $remembermehash}checked="checked"{/if} name="rememberme" value="1"><label for="login_remember">{lang text="JEGYEZZE_MEG"}</label>
          </div>
          <div class="col-md-6">
            <a href="/elfelejtett-jelszo"><span class="fa-stack">
              <i class="fa fa-square fa-stack-2x"></i>
              <i class="fa fa-question fa-stack-1x"></i>
            </span> {lang text="ELFELEJTETT_JELSZO"}</a>
          </div>
        </div>
      </div>
      <div class="sub line">
        <button type="submit" class="btn btn-success" name="ugyfelkapuLogin">{lang text="BELEPES"}</button>
      </div>
      <a href="/regisztracio" class="btn btn-info">{lang text="REGISZTRACIO"}</a>
    </div>
  </form>
  {/if}
</div>
