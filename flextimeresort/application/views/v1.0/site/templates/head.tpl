<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{$title}</title>
  {$SEOSERVICE}
  {include file='meta.tpl'}
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="{if $404page}page404{/if}">
<header>
  <div class="top">
    <div class="page-width">
      <nav>
        <ul class="navi">
          <li><a href="/belepes"><img src="{$smarty.const.IMG}icons/white/user.svg" class="i15" alt="{lang text="BELEPES"}">{lang text="BELEPES"}</a></li>
          <li><a href="/regisztracio"><img src="{$smarty.const.IMG}icons/white/lock.svg" class="i15" alt="{lang text="REGISZTRACIO"}">{lang text="REGISZTRACIO"}</a></li>
          <li><a href="/ugyfelkapu"><img src="{$smarty.const.IMG}icons/white/user_add.svg" class="i15" alt="{lang text="UGYFELKAPU"}">{lang text="UGYFELKAPU"}</a></li>
          <li><a href="/kapcsolat"><img src="{$smarty.const.IMG}icons/white/envelope.svg" class="i15" alt="{lang text="KAPCSOLAT"}">{lang text="KAPCSOLAT"}</a></li>
          <li class="social-before"></li>
          <li class="social facebook">
            <a href="#"><i class="fa fa-facebook"></i></a>
          </li>
          <li class="social googleplus">
            <a href="#"><i class="fa fa-google-plus"></i></a>
          </li>
          <li class="social twitter">
            <a href="#"><i class="fa fa-twitter"></i></a>
          </li>
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
        <li><a href="#">{lang text="MUNKAVALLALOKNAK"}</a></li>
        <li class="active"><a href="#">{lang text="MUNKALTATOKNAK"}</a></li>
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
