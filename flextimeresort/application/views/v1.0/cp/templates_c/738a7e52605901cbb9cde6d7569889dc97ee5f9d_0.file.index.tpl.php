<?php /* Smarty version 3.1.27, created on 2017-08-03 16:56:23
         compiled from "application/views/v1.0/cp/templates/userRequestAd/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:508339841598339974193d4_97434298%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '738a7e52605901cbb9cde6d7569889dc97ee5f9d' => 
    array (
      0 => 'application/views/v1.0/cp/templates/userRequestAd/index.tpl',
      1 => 1501772181,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '508339841598339974193d4_97434298',
  'variables' => 
  array (
    'requests' => 0,
    'item' => 0,
    'creator' => 0,
    'author' => 0,
    'admin' => 0,
    'root' => 0,
    'u' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5983399746bf75_90683474',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5983399746bf75_90683474')) {
function content_5983399746bf75_90683474 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '508339841598339974193d4_97434298';
?>
<h1>Munkavállaló adatigénylés</h1>
<div class="subtitle">
  Az alábbi listában szerepelnek azok az igénylések, melyet a munkavállalók adtak le egy állásajánlatuk kapcsán munkavállalók személyes adatai iránt.
</div>
<div class="user-request">
  <?php while ($_smarty_tpl->tpl_vars['requests']->value->walk()) {?>
    <?php $_smarty_tpl->tpl_vars["item"] = new Smarty_Variable($_smarty_tpl->tpl_vars['requests']->value->get(), null, 0);?>
    <div class="allas id<?php echo $_smarty_tpl->tpl_vars['item']->value['ID'];?>
">
      <div class="header">
        <div class="author-info">
          <?php $_smarty_tpl->tpl_vars["creator"] = new Smarty_Variable($_smarty_tpl->tpl_vars['item']->value['data']->createdBy(), null, 0);?>
          <?php $_smarty_tpl->tpl_vars["author"] = new Smarty_Variable($_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('author'), null, 0);?>
          <span class="creator">Létrehozta: <strong><?php echo $_smarty_tpl->tpl_vars['creator']->value['name'];?>
</strong> <span class="by by-<?php echo $_smarty_tpl->tpl_vars['creator']->value['by'];?>
"><?php echo $_smarty_tpl->tpl_vars['creator']->value['by'];?>
</span></span>
          <span class="author">Hirdető: <?php if (is_null($_smarty_tpl->tpl_vars['author']->value->getName())) {?><em>- nincs hirdető adat -</em><?php } else { ?><strong><?php echo $_smarty_tpl->tpl_vars['author']->value->getName();?>
</strong><?php }?></span>
        </div>
        <div class="desc">
          <a title="Hirdetés adatlap" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['data']->getURL();?>
?atoken=<?php echo $_smarty_tpl->tpl_vars['admin']->value->getToken();?>
&showfull=1" target="_blank"><?php echo $_smarty_tpl->tpl_vars['item']->value['data']->shortDesc();?>
</a>
        </div>
        <div class="ads-data">
          <span class="type"><?php echo $_smarty_tpl->tpl_vars['item']->value['data']->get('tipus_name');?>
</span> /
          <span class="cat"><?php echo $_smarty_tpl->tpl_vars['item']->value['data']->get('cat_name');?>
</span>
          <span class="city"><i class="fa fa-map-marker"></i> <?php echo $_smarty_tpl->tpl_vars['item']->value['data']->getCity();?>
</span>
          <span class="edit"><a target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
ads/editor/<?php echo $_smarty_tpl->tpl_vars['item']->value['ID'];?>
"><i class="fa fa-pencil"></i> szerkeszt</a></span>
        </div>
        <div class="contact">
          <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('phone'))) {?>
          <span data-toggle="tooltip" data-placement="bottom" title="Megadott kapcsolat telefonszám"><i class="fa fa-phone"></i> <?php echo $_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('phone');?>
</span>
          <?php }?>
          <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('email'))) {?>
          <span data-toggle="tooltip" data-placement="bottom" title="Megadott kapcsolati e-mail cím"><i class="fa fa-envelope"></i> <?php echo $_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('email');?>
</span>
          <?php }?>
          <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('name'))) {?>
          <span data-toggle="tooltip" data-placement="bottom" title="Megadott hirdető neve"><i class="fa fa-address-card"></i> <?php echo $_smarty_tpl->tpl_vars['item']->value['data']->getAuthorData('name');?>
</span>
          <?php }?>
        </div>
      </div>
      <div class="requests">
        <div class="info status-<?php if ($_smarty_tpl->tpl_vars['requests']->value->request_info[$_smarty_tpl->tpl_vars['item']->value['ID']]['requests']['untouched'] && $_smarty_tpl->tpl_vars['requests']->value->request_info[$_smarty_tpl->tpl_vars['item']->value['ID']]['requests']['untouched'] != 0) {?>hasnotaccept<?php } else { ?>allaccepted<?php }?>">
          <?php echo count($_smarty_tpl->tpl_vars['item']->value['items']);?>
 megjelölt munkavállaló <?php if ($_smarty_tpl->tpl_vars['requests']->value->request_info[$_smarty_tpl->tpl_vars['item']->value['ID']]['requests']['untouched'] && $_smarty_tpl->tpl_vars['requests']->value->request_info[$_smarty_tpl->tpl_vars['item']->value['ID']]['requests']['untouched'] != 0) {?>/ <strong> Ebből <?php echo $_smarty_tpl->tpl_vars['requests']->value->request_info[$_smarty_tpl->tpl_vars['item']->value['ID']]['requests']['untouched'];?>
 db feldolgozásra várakozik!</strong><?php }?> <a href="javascript:void(0)" onclick="openRequests(<?php echo $_smarty_tpl->tpl_vars['item']->value['ID'];?>
)">Megnyitás</a>
        </div>
        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['items'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['u'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['u']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->_loop = true;
$foreach_u_Sav = $_smarty_tpl->tpl_vars['u'];
?>

        <div class="user request ads<?php echo $_smarty_tpl->tpl_vars['item']->value['ID'];?>
 gender">
          <div class="wrapper">
            <div class="profilimg">
              <img src="" alt="">
            </div>
            <div class="dataset">
              <div class="name">
                <a href="" target="_blank"><?php echo print_r($_smarty_tpl->tpl_vars['u']->value);?>
</a>
              </div>
              <div class="szakma">

              </div>
              <div class="subline">
                <span class="city"></span>
              </div>
            </div>
            <div class="status">
              <div class="date">
                <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Felvéve"),$_smarty_tpl);?>
: <strong></strong>
              </div>
              <div class="user-decide">
                <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Felhasználó visszajelzés"),$_smarty_tpl);?>
:
                <strong>
                <span ng-if="u.feedback == -1" class="inprogress"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Kapcsolatfelvétel alatt"),$_smarty_tpl);?>
.</span>
                <span ng-if="u.feedback == 0" class="declined"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Kapcsolat felvéve: ajánlat nem érdekli"),$_smarty_tpl);?>
.</span>
                <span ng-if="u.feedback == 1" class="accept"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Kapcsolat felvéve: ajánlat érdekli"),$_smarty_tpl);?>
.</span>
                </strong>
              </div>
              <div class="access-granted">
                <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Teljes hozzáférés megadva"),$_smarty_tpl);?>
: <strong><span ng-if="u.access_granted" class="yes"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Igen"),$_smarty_tpl);?>
 ()</span> <span  ng-if="!u.access_granted" class="no"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Nem"),$_smarty_tpl);?>
</span></strong>
              </div>
              <div class="acess-date" ng-if="u.granted_date_at">
                <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['lang'][0][0]->language_translator(array('text'=>"Hozzáférés lejárati ideje"),$_smarty_tpl);?>
: <strong></strong>
              </div>
            </div>
          </div>
        </div>
        <?php
$_smarty_tpl->tpl_vars['u'] = $foreach_u_Sav;
}
?>
      </div>
    </div>
  <?php }?>
</div>

  <?php echo '<script'; ?>
 type="text/javascript">
    function openRequests(adid) {
      $('.allas').addClass('defocus').removeClass('focus');
      $('.allas.id'+adid).removeClass('defocus').addClass('focus');
      $('.request.opened').removeClass('opened');
      $('.request.ads'+adid).addClass('opened');
    }
  <?php echo '</script'; ?>
>

<pre><?php echo print_r($_smarty_tpl->tpl_vars['requests']->value->request_info);?>
</pre>
<?php }
}
?>