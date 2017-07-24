<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{$settings.page_title|strip_tags}</title>
  {include file='meta.tpl'}
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="admin hold-transition {if "IS_DEV"|defined}dev{/if} {if $admin->logged}skin-green sidebar-mini{else}login-page{/if}">
<div id="pagepreloader">
  <div class="loadwrapper">
    <i class="fa fa-spin fa-spinner"></i>
    <div class="">
      Adatok betöltése...
    </div>
  </div>
</div>
<div class="wrapper">
{if !$admin->logged}
<!-- Login module -->
<div id="login">
  <div class="logologin">
    <img src="{$smarty.const.IMG}logo-single-horizontal-greenbg.svg" alt="{$settings.page_title}">
  </div>
  <div class="login-wrapper">
      <div class="">
        <h3>Adminisztráció bejelentkezés</h3>
          {$form->getMsg(1)}
          <form action="/cp/forms" method="post">
            <input type="hidden" name="return" value="{$settings.page_url}{$settings.admin_root}">
            <input type="hidden" name="form" value="1">
            <input type="hidden" name="for" value="login_admin">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" class="form-control" name="user">
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input type="password" class="form-control" name="pw">
            </div>
            <br>
            <div class="row col-vertical-middle">
              <div class="col-md-6 left"><a href="{$settings.page_url}"><i class="fa fa-angle-left"></i> {$settings.page_url}</a></div>
              <div class="col-md-6 right"><button name="login" class="btn btn-success">Bejelentkezés <i class="fa fa-angle-right"></i></button></div>
            </div>
          </form>
      </div>
    </div>
    <div class="copyright">
      {$settings.page_title}
    </div>
</div>
<!--/Login module -->
{/if}

{if $admin->logged}
<header class="main-header col-vertical-middle">
  <a href="{$settings.page_url}{$settings.admin_root}" class="logo">
    <div class="logo-holder">
      <img src="{$smarty.const.IMG}logo-embleme-only.svg" alt="{$settings.page_title}">
      <span class="authortext">{$settings.page_title}</span>
    </div>
  </a>
  <div class="navbar col-vertical-middle">
    <div class="navi">
      <ul>
        <li class="toggler"><a href="#" class="sidebar-toggle"><i class="fa fa-bars"></i></a></li>
        <li class="quicknav">
          <ul class="quicknav">
            <li><a href="#"><strong>{$admin->getName()}</strong> <small>({$admin->getUsername()})</small></a></li>
            <li class="logout"><a data-toggle="tooltip" data-placement="left" title="Kijelentkezés" href="{$root}logout"><i class="fa fa-times"></i></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</header>

<aside class="main-sidebar">
  <section class="menu">
    <ul>
      <li class="basehead">Navigáció</li>
      <li class="{if $GETS[1] == 'ads' && $GETS[2] != 'requests'}active{/if}"><a href="{$root}ads"><i class="fa fa-file-text"></i> <span class="text">Állásajánlatok</span></a></li>
      {if $GETS[1] == 'ads' && $GETS[2] != 'requests'}
        <li class="sub {if $GETS[2] == 'editor'}active{/if}"><a href="{$root}ads/editor"><i class="fa fa-plus-circle"></i> <span class="text">Új hirdetés</span></a></li>
      {/if}
      <li class="{if $GETS[2] == 'requests'}active{/if}"><a href="{$root}ads/requests"><i class="fa fa-mouse-pointer"></i> <span class="text">Jelentkezések</span><span class="notify pull-right waiting_ad_applicant_ntf"></span></a></li>
      <li class="{if $GETS[1] == 'messanger'}active{/if}"><a href="{$root}messanger/outbox"><i class="fa fa-comments-o"></i> <span class="text">Üzenetek</span><span class="notify pull-right unwatched_messages_ntf"></span></a></li>
      <li class="{if $GETS[1] == 'users'}active{/if}"><a href="{$root}users"><i class="fa fa-users"></i> <span class="text">Felhasználók</span></a></li>
      <li class="{if $GETS[1] == 'oldalak'}active{/if}"><a href="{$root}oldalak"><i class="fa fa-file-o"></i> <span class="text">Oldalak</span></a></li>
      <li class="{if $GETS[1] == 'menu'}active{/if}"><a href="{$root}menu"><i class="fa fa-th"></i> <span class="text">Menük</span></a></li>
      <li class="{if $GETS[1] == 'cikkek'}active{/if}"><a href="{$root}cikkek"><i class="fa fa-book"></i> <span class="text">Cikkek</span></a></li>
      <li class="{if $smarty.get.tag == 'cp/terms'}active{/if}"><a href="{$root}cat/"><i class="fa fa-bars"></i> <span class="text">Tematikus listák</span></a></li>
      {if $smarty.get.tag == 'cp/terms'}
        {foreach from=$tematic_list item=tl}
        <li class="sub {if $smarty.get.groupkey == $tl.termkey}active{/if}"><a {if !empty($tl.description)}data-toggle="tooltip" data-placement="right" title="{$tl.description}"{/if} href="{$root}cat-{$tl.termkey}"><i class="fa fa-{$tl.faico}"></i> <span class="text">{$tl.neve}</span></a></li>
        {/foreach}
      {/if}
      {if $admin && $admin->getPrivIndex() == 0}
        <li class="{if $GETS[1] == 'settings'}active{/if}"><a href="{$root}settings"><i class="fa fa-gear"></i> <span class="text">Beállítások</span></a></li>
      {/if}
    </ul>
  </section>
</aside>
<div class="content-wrapper">
{/if}
