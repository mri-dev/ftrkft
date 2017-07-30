<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{$title}</title>
  <link rel="canonical" href="{if $canonical_url}{$canonical_url}{else}{$settings.page_url|cat:$smarty.server.REQUEST_URI}{/if}" />
  {$SEOSERVICE}
  {include file='meta.tpl'}
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="profil-view munkavallalo {$bodyclass}">
<div class="content-holder">
  <header>
    <div class="page-width">
      <div class="hwrapper">
        <div class="company-copy">
          <a href="/"><img class="logo" src="{$smarty.const.IMG}logo-single-horizontal.svg" alt="{$settings.page_title|strip_tags}"></a>
        </div>
        <div class="action-buttons">
          <a href=""><i class="fa fa-envelope"></i></a>
          <a href=""><i class="fa fa-print"></i></a>
        </div>
      </div>
    </div>
  </header>
  <div class="clearfix"></div>
  <div class="inside page-width">
