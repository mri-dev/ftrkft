<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{$title}</title>
  <link rel="shortcut icon" type="image/png" href="{$smarty.const.IMG}/favicon.png"/>
  <link rel="canonical" href="{if $canonical_url}{$canonical_url}{else}{$settings.page_url|cat:$smarty.server.REQUEST_URI}{/if}" />
  {$SEOSERVICE}
  {include file='meta.tpl'}
  <meta property="fb:app_id" content="{$settings.FACEBOOK_APP_ID}" />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="{$bodyclass}{if $404page}page404{/if}">
<header>
  <div class="top">
    <div class="page-width">
      <nav>
        <ul class="navi">
          <li class="languages">
            {if !empty($active_langs) && count($active_langs) > 1}
            <div class="selected">
              {lang text="Nyelv"}: <strong>{$active_langs[$current_lang].nametext}</strong> <i class="fa fa-caret-down"></i>
            </div>
            <div class="list">
              {foreach from=$active_langs item=lang}
                <div class="{if $current_lang == $lang.code}current{/if}"><a href="{$settings.page_url}?language={$lang.code}">{$lang.nametext}</a></div>
              {/foreach}
            </div>
            {/if}
          </li>
          {if $me && $me->logged()}
          <li class="account hide-on-mobile"><a href="/ugyfelkapu">{lang text="BEJELENTKEZVE_MINT_XY" who=$me->getName()}</a></li>
          <li class="account show-on-mobile"><a href="/ugyfelkapu">{if $me && $me->logged() && $total_notify && $total_notify != 0}
            <span class="notify">{$total_notify}</span>
          {/if}<i class="fa fa-user-circle"></i> {$me->getName()}</a></li>
          <li class="account account-logout"><a data-toggle="tooltip" data-placement="bottom" title="{lang text='KIJELENTKEZES'}" href="/ugyfelkapu/?logout=1"><i class="fa fa-sign-out"></i></a></li>
          {else}
          <li><a href="/belepes"><img src="{$smarty.const.IMG}icons/white/user.svg" class="i15" alt="{lang text="BELEPES"}">{lang text="BELEPES"}</a></li>
          <li class="hide-on-mobile"><a href="/regisztracio"><img src="{$smarty.const.IMG}icons/white/lock.svg" class="i15" alt="{lang text="REGISZTRACIO"}">{lang text="REGISZTRACIO"}</a></li>
          {/if}
          <li class="hide-on-mobile"><a href="/ugyfelkapu"><img src="{$smarty.const.IMG}icons/white/user_add.svg" class="i15" alt="{lang text="UGYFELKAPU"}">{lang text="UGYFELKAPU"}{if $me && $me->logged() && $total_notify && $total_notify != 0}
            <span class="notify">{$total_notify}</span>
          {/if}</a></li>
          {if $me && $me->logged() && $me->isUser()}
            <li class="cv-link"><a href="{$me->getCVUrl()}"><i class="fa fa-file-text-o"></i> {lang text="Önéletrajzom"}</a></li>
          {/if}
          <li class="hide-on-mobile"><a href="/kapcsolat"><img src="{$smarty.const.IMG}icons/white/envelope.svg" class="i15" alt="{lang text="KAPCSOLAT"}">{lang text="KAPCSOLAT"}</a></li>
          <li class="social-before"></li>
          {if !empty($settings.links_social_facebook)}
          <li class="social facebook">
            <a href="{$settings.links_social_facebook}" target="_blank"><i class="fa fa-facebook"></i></a>
          </li>
          {/if}
          {if !empty($settings.links_social_googleplus)}
          <li class="social googleplus">
            <a href="{$settings.links_social_googleplus}" target="_blank"><i class="fa fa-google-plus"></i></a>
          </li>
          {/if}
          {if !empty($settings.links_social_twitter)}
          <li class="social twitter">
            <a href="{$settings.links_social_twitter}" target="_blank"><i class="fa fa-twitter"></i></a>
          </li>
          {/if}
        </ul>
        <div class="helper"></div>
      </nav>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="page-width">
    <div class="main row">
      <div class="logo col-md-5">
        <a href="/"><img src="{$smarty.const.IMG}logo-single-horizontal.svg" alt="{$settings.page_title|strip_tags}"></a>
      </div>
      <div class="slogan col-md-7">
        <h4>{lang text="PAGESLOGAN"}</h4>
      </div>
    </div>
  </div>
  <nav>
    <div class="page-width">
      <ul class="navi pull-left main-nav">
        <li class="{if !$me->logged() || ($me->logged() && $me->isUser())}active{/if}"><a href="{$settings.allas_search_slug}">{lang text="MUNKAVALLALOKNAK"}</a></li>
        <li class="{if ($me && $me->logged() &&$me->isMunkaado())}active{/if}"><a href="{$settings.munkavallalo_search_slug}">{lang text="MUNKALTATOKNAK"}</a></li>
      </ul>
      <ul class="navi pull-right sec-nav">
        {foreach from=$menu_header item=menu}
          <li>
            <a href="{$menu.url}">{if !$defaultlang && !empty($menu.langkey)}{lang text=$menu.langkey}{else}{$menu.nev}{/if}</a>
            {if count($menu.child) != 0}
              <ul class="submenu childof{$menu.ID}">
              {foreach from=$menu.child item=ma}
                <li>
                  <a href="{$ma.url}">{if !$defaultlang && !empty($ma.langkey)}{lang text=$ma.langkey}{else}{$ma.nev}{/if}</a>
                </li>
              {/foreach}
              </ul>
            {/if}
          </li>
        {/foreach}
      </ul>
    </div>
    <div class="clearfix"></div>
  </nav>
</header>
<div class="content-holder">
{if !$hidehometop}
  {include file='inc/home_top.tpl'}
{/if}
{if $show_ugyfelkapu_top}
  {include file='inc/ugyfelkapu_top.tpl'}
{/if}
