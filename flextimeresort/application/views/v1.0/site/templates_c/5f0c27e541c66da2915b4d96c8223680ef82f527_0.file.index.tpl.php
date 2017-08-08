<?php /* Smarty version 3.1.27, created on 2017-08-08 18:39:24
         compiled from "application/views/v1.0/site/templates/home/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:19634494075989e93ce93a94_33367640%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5f0c27e541c66da2915b4d96c8223680ef82f527' => 
    array (
      0 => 'application/views/v1.0/site/templates/home/index.tpl',
      1 => 1502210363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19634494075989e93ce93a94_33367640',
  'variables' => 
  array (
    'lang' => 0,
    'settings' => 0,
    'articles_top' => 0,
    'articles_more' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5989e93cec69b9_86111838',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5989e93cec69b9_86111838')) {
function content_5989e93cec69b9_86111838 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '19634494075989e93ce93a94_33367640';
?>
<div class="content-inside homepage">
  <div class="page-width">
    <div class="row">
      <div class="col-md-9">
        <?php echo $_smarty_tpl->tpl_vars['lang']->value;?>

        <div class="headerline center-mode bold">
          <h2><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Legújabb álláshirdetések"),$_smarty_tpl);?>
</h2>
          <div class="line"></div>
        </div>

        <div class="allasok style-new-home">
          <?php echo $_smarty_tpl->getSubTemplate ('inc/allasok.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

        </div>
        <div class="more-allas">
          <a href="<?php echo $_smarty_tpl->tpl_vars['settings']->value['allas_search_slug'];?>
"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"További állások keresése"),$_smarty_tpl);?>
 <i class="fa fa-arrow-circle-right"></i></a>
        </div>

        <div class="headerline">
          <h2><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Aktuális"),$_smarty_tpl);?>
</h2>
          <div class="line"></div>
        </div>

        <div class="articles style-box-col3">
          <?php while ($_smarty_tpl->tpl_vars['articles_top']->value->walk()) {?>
            <article class="">
              <div class="wrapper">
                <div class="img">
                  <a href="<?php echo $_smarty_tpl->tpl_vars['articles_top']->value->URL();?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['articles_top']->value->Image();?>
" alt="<?php echo $_smarty_tpl->tpl_vars['articles_top']->value->getTitle();?>
"></a>
                </div>
                <div class="title">
                  <h3><a href="<?php echo $_smarty_tpl->tpl_vars['articles_top']->value->URL();?>
"><?php echo $_smarty_tpl->tpl_vars['articles_top']->value->getTitle();?>
</a></h3>
                </div>
                <div class="desc">
                  <?php echo $_smarty_tpl->tpl_vars['articles_top']->value->getSEODesc();?>

                </div>
                <div class="nav">
                  <a href="<?php echo $_smarty_tpl->tpl_vars['articles_top']->value->URL();?>
"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"A teljes cikk megtekintése"),$_smarty_tpl);?>
</a>
                </div>
              </div>
            </article>
          <?php }?>
        </div>

        <div class="headerline">
          <h2><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"További cikkeink"),$_smarty_tpl);?>
</h2>
          <div class="line"></div>
        </div>

        <div class="articles style-clear-col2">
          <?php while ($_smarty_tpl->tpl_vars['articles_more']->value->walk()) {?>
            <article class="">
              <div class="wrapper">
                <div class="title">
                  <h3><a href="<?php echo $_smarty_tpl->tpl_vars['articles_more']->value->URL();?>
"><?php echo $_smarty_tpl->tpl_vars['articles_more']->value->getTitle();?>
</a></h3>
                </div>
                <div class="desc">
                  <?php echo $_smarty_tpl->tpl_vars['articles_more']->value->getSEODesc();?>
 <a class="read" href="<?php echo $_smarty_tpl->tpl_vars['articles_more']->value->URL();?>
"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Tovább"),$_smarty_tpl);?>
>></a>
                </div>
              </div>
            </article>
          <?php }?>
        </div>
      </div>
      <div class="col-md-3">
        <?php echo $_smarty_tpl->getSubTemplate ('inc/sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0);
?>

      </div>
    </div>
  </div>
</div>
<?php }
}
?>