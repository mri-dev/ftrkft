<h1>Oldalak</h1>
{if $form}
  {$form->getMsg(1)}
{/if}
<div class="row">
  <div class="col-md-12">
    <div class="box {if $GETS[2] == 'edit'}editor{elseif $GETS[2] =='del'}delete{/if}">
      <form class="" action="/forms/pages" method="post">
        <input type="hidden" name="form" value="1">
        <input type="hidden" name="session_path" value="{$root}pages">
        {if $GETS[2] == 'add' || empty($GETS[2])}
        <input type="hidden" name="return" value="{$root}pages/">
        <input type="hidden" name="for" value="add">
        <h3><i class="fa fa-plus-circle"></i> Oldal hozzáadása</h3>
        {elseif $GETS[2] == 'edit'}
        <input type="hidden" name="return" value="{$root}pages/edit/{$GETS[3]}">
        <input type="hidden" name="for" value="edit">
        <input type="hidden" name="id" value="{$check->getId()}">
        <h3><i class="fa fa-pencil"></i> Oldal szerkesztés</h3>
        {/if}
        {if $GETS[2] != 'del'}
        <div class="row">
          <div class="col-md-5">
            <label for="cim">Oldal címe*</label>
            <input type="text" id="cim" class="form-control" name="cim" value="{if $check}{$check->getTitle()}{else}{$form->getPost('cim')}{/if}">
          </div>
          <div class="col-md-3">
            <label for="langkey">Oldal cím nyelvi azonosító kulcs</label>
            <input type="text" id="langkey" class="form-control" name="langkey" value="{if $check}{$check->getLangkey()}{else}{$form->getPost('langkey')}{/if}">
          </div>
          <div class="col-md-4">
            <label for="eleres">Egyedi SEO URL utótag</label>
            <input type="text" id="eleres" min="-100" step="1" class="form-control" name="eleres" value="{if $check}{$check->getUrl()}{else}{$form->getPost('eleres')}{/if}">
            <div class="comment">
              Ne használjon ékezeteket és üres, szóköz karaktereket. Helyes példák.: fooldal, kapcsolat, altalanos_szerzodesi_feltetelek
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="szoveg">Tartalom</label>
            <textarea name="szoveg" id="szoveg" class="form-control editor">{if $check}{$check->getHtmlContent()}{else}{$form->getPost('szoveg')}{/if}</textarea>
          </div>
        </div>
        <br>
        <div class="row col-vertical-middle">
          <div class="col-md-8">
            <label for="kulcsszavak">Kulcsszavak</label>
            <input type="text" id="kulcsszavak" min="-100" step="1" class="form-control" name="kulcsszavak" value="{if $check}{$check->getKeywords()}{else}{$form->getPost('kulcsszavak')}{/if}">
          </div>
          <div class="col-md-2">
            <label for="sorrend">Sorrend</label>
            <input type="number" id="sorrend" min="-100" step="1" class="form-control" name="sorrend" value="{if $check}{$check->getOrderIndex()}{else}{$form->getPost('sorrend')}{/if}">
          </div>
          <div class="col-md-2">
            <input type="checkbox" class="ccb" {if $check && $check->getVisibility()}checked="checked"{elseif $form->getPost('lathato') == 'on'}checked="checked"{/if} name="lathato" id="lathato" value="on"> <label for="lathato"> Aktív</label>
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
        {else}
        <input type="hidden" name="return" value="{$root}menu/">
        <input type="hidden" name="for" value="del">
        <input type="hidden" name="id" value="{$check->getId()}">
        <h3><i class="fa fa-trash"></i> Elem törlése</h3>
        Biztos, hogy törli a(z) <strong>{$check->getTitle()}</strong> menü elemet? A művelet nem visszavonható!
        <br><br>
        <div class="row">
          <div class="col-md-12 right">
            <a href="{$root}menu" class="btn btn-default pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
            <button type="submit" class="btn btn-danger">Végleges törlés</button>
          </div>
        </div>
        {/if}
      </form>
    </div>
  </div>
  <div class="col-md-12">
    <div class="box">
      <h3>Feltöltött oldalak</h3>
      <div class="menu-list">
        <div class="row row-head">
          <div class="col-md-7"><strong>Felirat</strong></div>
          <div class="col-md-2 center"><strong>Típus</strong></div>
          <div class="col-md-1 center"><strong>Sorrend</strong></div>
          <div class="col-md-1 center"><strong>Látható</strong></div>
          <div class="col-md-1 center"><i class="fa fa-gear"></i> </div>
        </div>
      {while $object->walk()}
        {assign var="menu" value=$object->the_menu()}
        {assign var="menutype" value=$object->the_menu_type()}
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
              <a href="{$root}menu/del/{$menu.ID}"><i class="fa fa-trash"></i></a>
            </div>
          </div>
        </div>
      {/while}
      </div>
    </div>
  </div>
</div>
