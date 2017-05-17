<h1>Menük</h1>
{if $form}
  {$form->getMsg(1)}
{/if}
<div class="row">
  <div class="col-md-4">
    <div class="box {if $GETS[2] == 'edit'}editor{/if}">
      <form class="" action="/forms/menu" method="post">
        <input type="hidden" name="form" value="1">
        <input type="hidden" name="session_path" value="{$root}menu">
        {if $GETS[2] == 'add' || empty($GETS[2])}
        <input type="hidden" name="return" value="{$root}menu/">
        <input type="hidden" name="for" value="add">
        <h3><i class="fa fa-plus-circle"></i> Elem hozzáadása</h3>
        {elseif $GETS[2] == 'edit'}
        <input type="hidden" name="return" value="{$root}menu/edit/{$GETS[3]}">
        <input type="hidden" name="for" value="edit">
        <input type="hidden" name="id" value="{$check->getId()}">
        <h3><i class="fa fa-pencil"></i> Elem szerkesztés</h3>
        {/if}
        <div class="row">
          <div class="col-md-12">
            <label for="nev">Felirat*</label>
            <input type="text" id="nev" class="form-control" name="nev" value="{if $check}{$check->getTitle()}{else}{$form->getPost('nev')}{/if}">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="menu_pos">Menü pozíció*</label>
            <select class="form-control" name="menu_pos" id="menu_pos">
              <option value="" selected="selected">-- válasszon --</option>
              <option value="header" {if $check && $check->getPosition() == 'header'}selected="selected"{elseif $form->getPost('menu_pos') == 'header'}selected="selected"{/if}>Fejrész</option>
              <option value="footer" {if $check && $check->getPosition() == 'footer'}selected="selected"{elseif $form->getPost('menu_pos') == 'footer'}selected="selected"{/if}>Lábrész</option>
            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="menu_type">Menü típus*</label>
            <select class="form-control" name="menu_type" id="menu_type">
              <option value="" selected="selected">-- válasszon --</option>
              {foreach from=$menus->getTypes() item=v key=key }
                <option value="{$key}" {if $check && $check->getType() == $key}selected="selected"{elseif $form->getPost('menu_type') == $key}selected="selected"{/if}>{$v}</option>
              {/foreach}
            </select>
          </div>
        </div>
        <div class="row menu_type_sections" id="menu_type_url" style="{if ($check && $check->getType() == 'url') || $form->getPost('url') != ''}display: block;{else}display:none;{/if}">
          <div class="col-md-12">
            <br>
            <label for="url">URL</label>
            <input type="text" id="url" class="form-control" name="url" value="{if $check}{$check->getUrl()}{else}{$form->getPost('url')}{/if}">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="parent">Szülő menü</label>
            <select class="form-control" name="parent" id="parent">
              <option value="" selected="selected">-- ne legyen --</option>
              {while $menus->walk()} {assign var="menu" value=$menus->the_menu()}
                <option value="{$menu.ID}_{$menu.deep}" {if $check && $check->getParentId() == $menu.ID}selected="selected"{elseif $form->getPost('parent') == $menu.ID}selected="selected"{/if}>{$menu.nev}</option>
              {/while}
            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="sorrend">Sorrend</label>
            <input type="number" id="sorrend" min="-100" step="1" class="form-control" name="sorrend" value="{if $check}{$check->getSortNumber()}{else}{$form->getPost('sorrend')}{/if}">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <input type="checkbox" class="ccb" {if $check && $check->isVisible()}checked="checked"{elseif $form->getPost('lathato') == 'on'}checked="checked"{/if} name="lathato" id="lathato" value="on"> <label for="lathato"> Aktív</label>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 right">
            {if $GETS[2] == 'edit'}
            <a href="{$root}menu" class="btn btn-danger pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
            {/if}
            <button type="submit" class="btn btn-success">Mehet</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box">
      <h3>Menü elemek</h3>
      <div class="menu-list">
        <div class="row row-head">
          <div class="col-md-7"><strong>Felirat</strong></div>
          <div class="col-md-2 center"><strong>Típus</strong></div>
          <div class="col-md-1 center"><strong>Sorrend</strong></div>
          <div class="col-md-1 center"><strong>Látható</strong></div>
          <div class="col-md-1 center"><i class="fa fa-gear"></i> </div>
        </div>
      {while $menus->walk()}
        {assign var="menu" value=$menus->the_menu()}
        {assign var="menutype" value=$menus->the_menu_type()}
        <div class="mi deep{$menu.deep}">
          <div class="row col-vertical-middle">
            <div class="col-md-7">
              <div class="title"><strong>{$menu.nev}</strong></div>
              <span class="position">{$menu.gyujto}</span>
              {if $menutype.type == 'url'}
              <span class="url">
                URL: <a href="{$menu.url}" target="_blank">{$menu.url}</a>
              </span>
              {/if}
            </div>
            <div class="col-md-2 center">
              {$menutype.type}
            </div>
            <div class="col-md-1 center">{$menu.sorrend}</div>
            <div class="col-md-1 center">
              {if $menu.lathato == '1'}
              <i class="fa fa-check"></i>
              {else}
              <i class="fa fa-times"></i>
              {/if}
            </div>
            <div class="col-md-1 center actions">
              <a href="{$root}menu/edit/{$menu.ID}"><i class="fa fa-pencil"></i></a>
              <a href="#"><i class="fa fa-trash"></i></a>
            </div>
          </div>
        </div>
      {/while}
      </div>
    </div>
  </div>
</div>
{literal}
  <script type="text/javascript">
    $('#menu_type').change(function(){
      var sel = $(this).val();
      $('.menu_type_sections').hide(0);
      $('#menu_type_'+sel).show(0);
      $('#menu_type_'+sel).find('input').focus();
    });
  </script>
{/literal}
