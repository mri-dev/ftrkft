<h1>Cikkek</h1>
{if $form}
  {$form->getMsg(1)}
{/if}
<div class="row">
  <div class="col-md-12">
    <div class="box {if $GETS[2] == 'edit'}editor{elseif $GETS[2] =='del'}delete{/if}">
      <form class="" action="/forms/articles" method="post">
        <input type="hidden" name="form" value="1">
        <input type="hidden" name="session_path" value="{$root}cikkek">
        {if $GETS[2] == 'add' || empty($GETS[2])}
        <input type="hidden" name="return" value="{$root}cikkek/">
        <input type="hidden" name="for" value="add">
        <h3><i class="fa fa-plus-circle"></i> Cikk hozzáadása</h3>
        {elseif $GETS[2] == 'edit'}
        <input type="hidden" name="return" value="{$root}cikkek/edit/{$GETS[3]}">
        <input type="hidden" name="for" value="edit">
        <input type="hidden" name="id" value="{$check->getID()}">
        <h3><i class="fa fa-pencil"></i> Cikk szerkesztés</h3>
        {/if}
        {if $GETS[2] != 'del'}
        <div class="row">
          <div class="col-md-8">
            <label for="cim">Cikk címe*</label>
            <input type="text" id="cim" class="form-control" maxlength="60" name="title" value="{if $check}{$check->getTitle()}{else}{$form->getPost('title')}{/if}">
            <div class="comment">
              Maximum 60 karakter hosszú.
            </div>
          </div>
          <div class="col-md-4">
            <label for="slug">Egyedi SEO URL utótag <i data-toggle="tooltip" data-placement="top" title="Ne használjon ékezeteket és üres, szóköz karaktereket. Helyes példák.: uj_weboldalunk, stb.." class="fa fa-info-circle"></i></label>
            <input type="text" id="slug" min="-100" step="1" class="form-control" name="slug" value="{if $check}{$check->getSlug()}{else}{$form->getPost('slug')}{/if}">
            <div class="comment">
              <strong>Hagyja üressen, és automatikusan generálódik a címből.</strong>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="seo_desc">Rövid ismertető leírás (SEO)</label>
            <textarea name="seo_desc" id="seo_desc" maxlength="250" class="form-control">{if $check}{$check->getSEODesc()}{else}{$form->getPost('seo_desc')}{/if}</textarea>
            <div class="comment">
              Absztrakt &mdash; Ez fog megjelenni a listázásnál is, mint beharangozó szöveg. Hossza max. 250 karakter.
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="content">Cikk tartalom</label>
            <textarea name="content" id="content" class="form-control editor">{if $check}{$check->getHtmlContent()}{else}{$form->getPost('content')}{/if}</textarea>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="image">Cikk képe</label>
            <div class="input-group">
              <input type="text" id="image" class="form-control" name="image" value="{if $check}{$check->getImage()}{else}{$form->getPost('image')}{/if}">
              <span class="input-group-addon"><a data-toggle="tooltip" data-placement="left" title="Kattintson a kép tallózásához." href="/plugins/tinymce/plugins/filemanager/dialog.php?type=1&lang=hu_HU&field_id=image&subfolder=articles" data-fancybox-type="iframe" class="iframe-btn"><i class="fa fa-picture-o"></i></a></span>
            </div>
          </div>
        </div>
        <br>
        <div class="row col-vertical-middle">
          <div class="col-md-7">
            <label for="seo_keywords">Kulcsszavak <i data-toggle="tooltip" data-placement="top" title="Vesszővel válassza el a seo_keywordsat." class="fa fa-info-circle"></i></label>
            <input type="text" id="seo_keywords" min="-100" step="1" class="form-control" name="seo_keywords" value="{if $check}{$check->getKeywords()}{else}{$form->getPost('seo_keywords')}{/if}">
          </div>
          <div class="col-md-4">
            <label for="publish_date">Közzététel ideje</label>
            <input type="text" id="publish_after" class="form-control datepicker" name="publish_after" value="{if $check}{$check->getPublishAfter()}{else}{$form->getPost('publish_after')}{/if}">
          </div>
          <div class="col-md-1">
            <input type="checkbox" class="ccb" {if $check && $check->isActive()}checked="checked"{elseif $form->getPost('lathato') == 'on'}checked="checked"{/if} name="lathato" id="lathato" value="on"> <label for="lathato"> Aktív</label>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 right">
            {if $GETS[2] == 'edit'}
            <a href="{$root}cikkek" class="btn btn-danger pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
            {/if}
            <button type="submit" class="btn btn-success">Mehet</button>
          </div>
        </div>
        {else}
        <input type="hidden" name="return" value="{$root}oldalak/">
        <input type="hidden" name="for" value="del">
        <input type="hidden" name="id" value="{$check->getID()}">
        <h3><i class="fa fa-trash"></i> Elem törlése</h3>
        Biztos, hogy törli a(z) <strong>{$check->getTitle()}</strong> oldalt? A művelet nem visszavonható!
        <br><br>
        <div class="row">
          <div class="col-md-12 right">
            <a href="{$root}oldalak" class="btn btn-default pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
            <button type="submit" class="btn btn-danger">Végleges törlés</button>
          </div>
        </div>
        {/if}
      </form>
    </div>
  </div>
  <div class="col-md-12">
    <div class="box">
      <h3>Létrehozott cikkek</h3>
      <div class="menu-list">
        <div class="row row-head">
          <div class="col-md-6"><strong>Cikk címe</strong></div>
          <div class="col-md-2 center"><strong>Közzététel ideje</strong></div>
          <div class="col-md-2 center"><strong>Létrehozás ideje</strong></div>
          <div class="col-md-1 center"><strong>Látható</strong></div>
          <div class="col-md-1 center"><i class="fa fa-gear"></i> </div>
        </div>
      {if $ctrl}
        {while $ctrl->walk()}
          <div class="mi deep{$item.deep}">
            <div class="row col-vertical-middle">
              <div class="col-md-6">
                <div class="title"><strong>{$ctrl->getTitle()}</strong></div>
                <span class="url"><a href="{$settings.page_url}/cikk/{$ctrl->getSlug()}" target="_blank">{$settings.page_url}/cikk/<strong>{$ctrl->getSlug()}</strong></a></span>
                <div class="keywords">
                  {$ctrl->getKeywords()}
                </div>
              </div>
              <div class="col-md-2 center">{$ctrl->getPublishAfter()}</div>
              <div class="col-md-2 center">{$ctrl->getCreateDate()}</div>
              <div class="col-md-1 center">
                {if $ctrl->isActive() }
                <i class="fa fa-check"></i>
                {else}
                <i class="fa fa-times"></i>
                {/if}
              </div>
              <div class="col-md-1 center actions">
                <a href="{$root}cikkek/edit/{$ctrl->getID()}"><i class="fa fa-pencil"></i></a>
                <a href="{$root}cikkek/del/{$ctrl->getID()}"><i class="fa fa-trash"></i></a>
              </div>
            </div>
          </div>
        {/while}
      {/if}
      </div>
    </div>
  </div>
</div>
