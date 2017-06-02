{if empty($smarty.get.groupkey)}
<h1>Tematikus listák</h1>
{if $form}
  {$form->getMsg(1)}
{/if}
<form class="" action="/forms/terms" method="post">
  <div class="row">
    <div class="col-md-4">
      <div class="box {if $smarty.get.mod == 'edit'}editor{elseif $smarty.get.mod =='del'}delete{/if}">
          <input type="hidden" name="form" value="1">
          <input type="hidden" name="session_path" value="{$root}cat">
          {if $smarty.get.mod == 'add' || empty($smarty.get.mod)}
          <input type="hidden" name="return" value="{$root}cat/">
          <input type="hidden" name="for" value="addList">
          <h3><i class="fa fa-plus-circle"></i> Lista hozzáadása</h3>
          {elseif $smarty.get.mod == 'edit'}
          <input type="hidden" name="return" value="{$root}cat/edit/{$smarty.get.id}">
          <input type="hidden" name="for" value="editList">
          <input type="hidden" name="id" value="{$check.ID}">
          <h3><i class="fa fa-pencil"></i> Lista szerkesztés</h3>
          {/if}
          {if $smarty.get.mod != 'del'}
          <div class="row">
            <div class="col-md-12">
              <label for="neve">Elnevezés*</label>
              <input type="text" id="neve" class="form-control" name="neve" value="{if $check}{$check.neve}{else}{$form->getPost('neve')}{/if}">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="termkey">Azonosító kulcs*</label>
              <input type="text" id="termkey" class="form-control" name="termkey" value="{if $check}{$check.termkey}{else}{$form->getPost('termkey')}{/if}">
              <p class="comment">Egyedi kulcsnév. Pl.: megyek, nyelvismeret, jogositvanyok</p>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="description">Rövid ismertető leírás (note)</label>
              <textarea name="description" id="description" class="form-control">{if $check}{$check.description}{else}{$form->getPost('description')}{/if}</textarea>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 right">
              {if $smarty.get.mod == 'edit'}
              <a href="{$root}cat/" class="btn btn-danger pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
              {/if}
              <button type="submit" class="btn btn-success">Mehet</button>
            </div>
          </div>
          {else}
          <input type="hidden" name="return" value="{$root}cat/">
          <input type="hidden" name="for" value="delList">
          <input type="hidden" name="id" value="{$check.ID}">
          <h3><i class="fa fa-trash"></i> Elem törlése</h3>
          Biztos, hogy törli a(z) <strong>{$check.neve}</strong> tematikus listát? A művelet nem visszavonható!
          <br><br>
          <div class="row">
            <div class="col-md-12 right">
              <a href="{$root}cat/" class="btn btn-default pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
              <button type="submit" class="btn btn-danger">Végleges törlés</button>
            </div>
          </div>
          {/if}
      </div>
    </div>
    <div class="col-md-8">
      <div class="box">
        <h3>Liták</h3>
        <div class="menu-list">
          <div class="row row-head">
            <div class="col-md-3"><strong>Elnevezés</strong></div>
            <div class="col-md-5"><strong>Ismertető</strong></div>
            <div class="col-md-2 center"><strong>Azonosító kulcs</strong></div>
            <div class="col-md-2 center"><i class="fa fa-gear"></i> </div>
          </div>
          {foreach from=$term_list item=list}
          <div class="mi">
            <div class="row col-vertical-middle">
              <div class="col-md-3">
                <div class="title"><strong><a href="{$root}cat-{$list.termkey}">{$list.neve}</a></strong></div>
              </div>
              <div class="col-md-5">
                {$list.description}
              </div>
              <div class="col-md-2 center">{$list.termkey}</div>
              <div class="col-md-2 center actions">
                <a href="{$root}cat/edit/{$list.ID}"><i class="fa fa-pencil"></i></a>
                <a href="{$root}cat/del/{$list.ID}"><i class="fa fa-trash"></i></a>
              </div>
            </div>
          </div>
          {/foreach}
        </div>
      </div>
    </div>
  </div>
</form>

{else}

<h1><a class="backurl" href="{$root}cat/"><i class="fa fa-long-arrow-left"></i></a> Tematikus listák / <strong>{$list.neve}</strong></h1>
{if $form}
  {$form->getMsg(1)}
{/if}
<form class="" action="/forms/terms" method="post">
  <div class="row">
    <div class="col-md-4">
      <div class="box {if $smarty.get.mod == 'edit'}editor{elseif $smarty.get.mod =='del'}delete{/if}">
          <input type="hidden" name="form" value="1">
          <input type="hidden" name="session_path" value="{$root}cat-{$list.termkey}/">
          {if $smarty.get.mod == 'add' || empty($smarty.get.mod)}
          <input type="hidden" name="return" value="{$root}cat-{$list.termkey}/">
          <input type="hidden" name="for" value="add">
          <input type="hidden" name="groupkey" value="{$list.termkey}">
          <h3><i class="fa fa-plus-circle"></i> Elem hozzáadása</h3>
          {elseif $smarty.get.mod == 'edit'}
          <input type="hidden" name="return" value="{$root}cat-{$list.termkey}/edit/{$smarty.get.id}">
          <input type="hidden" name="for" value="edit">
          <input type="hidden" name="id" value="{$check->getId()}">
          <h3><i class="fa fa-pencil"></i> Elem szerkesztés</h3>
          {/if}
          {if $smarty.get.mod != 'del'}
          <div class="row">
            <div class="col-md-12">
              <label for="nev">Érték*</label>
              <input type="text" id="nev" class="form-control" name="nev" value="{if $check}{$check->getName()}{else}{$form->getPost('nev')}{/if}">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="langkey">Nyelvi szöveg azonosító kulcs</label>
              {assign var="langkeyprefix" value='TERMS_'|cat:$list.termkey|cat:'_'|strtoupper}
              <input type="hidden" name="langkeyprefix" value="{$langkeyprefix}">
              <div class="input-group">
                <span class="input-group-addon" style="font-size: 0.85em;">{$langkeyprefix}</span>
                <input type="text" id="langkey" class="form-control" name="langkey" value="{if $check}{$check->getLangKey()|replace:$langkeyprefix:''}{else}{$form->getPost('langkey')}{/if}">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="slug">SEO URL tag*</label>
              <input type="text" id="slug" class="form-control" name="slug" value="{if $check}{$check->getSlug()}{else}{$form->getPost('slug')}{/if}">
              <p class="comment">Pl.: egyedi_azonosito_url</p>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="parent">Szülő elem</label>
              <select class="form-control" name="parent" id="parent">
                <option value="" selected="selected">-- ne legyen --</option>
                {while $terms->walk()}
                  <option value="{$terms->getParentKey()}" {if $check && $check->getParentId() == $terms->getId()}selected="selected"{elseif $terms->getId() == $form->getPost('parent')}selected="selected"{/if}>{"&mdash; "|str_repeat:$terms->getDeep()}{$terms->getName()}</option>
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
            <div class="col-md-12 right">
              {if $smarty.get.mod == 'edit'}
              <a href="{$root}cat-{$list.termkey}" class="btn btn-danger pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
              {/if}
              <button type="submit" class="btn btn-success">Mehet</button>
            </div>
          </div>
          {else}
          <input type="hidden" name="return" value="{$root}cat-{$list.termkey}/">
          <input type="hidden" name="for" value="del">
          <input type="hidden" name="id" value="{$check->getId()}">
          <h3><i class="fa fa-trash"></i> Elem törlése</h3>
          Biztos, hogy törli a(z) <strong>{$check->getName()}</strong> tematikus listát? A művelet nem visszavonható!
          <br><br>
          <div class="row">
            <div class="col-md-12 right">
              <a href="{$root}cat/" class="btn btn-default pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
              <button type="submit" class="btn btn-danger">Végleges törlés</button>
            </div>
          </div>
          {/if}
      </div>
    </div>
    <div class="col-md-8">
      <div class="box">
        <h3>{$list.neve} &mdash; elemek</h3>
        <div class="menu-list">
          <div class="row row-head">
            <div class="col-md-6"><strong>Érték</strong></div>
            <div class="col-md-2 center"><strong>SEO tag</strong></div>
            <div class="col-md-2 center"><strong>Sorrend</strong></div>
            <div class="col-md-2 center"><i class="fa fa-gear"></i> </div>
          </div>
          {while $terms->walk()}
          <div class="mi deep{$terms->getDeep()}">
            <div class="row col-vertical-middle">
              <div class="col-md-6">
                <div class="title"><strong>{$terms->getName()}</strong></div>
                <div class="translator">
                  <span class=key>{$terms->getLangKey()}</span>
                </div>
              </div>
              <div class="col-md-2 center">
                {$terms->getSlug()}
              </div>
              <div class="col-md-2 center">
                {$terms->getSortIndex()}
              </div>
              <div class="col-md-2 center actions">
                <a href="{$root}cat-{$list.termkey}/edit/{$terms->getID()}"><i class="fa fa-pencil"></i></a>
                <a href="{$root}cat-{$list.termkey}/del/{$terms->getID()}"><i class="fa fa-trash"></i></a>
              </div>
            </div>
          </div>
          {/while}
        </div>
      </div>
    </div>
  </div>
</form>

{/if}
