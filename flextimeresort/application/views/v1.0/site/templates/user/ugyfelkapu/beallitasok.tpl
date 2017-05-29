<div class="group">
  <a name="jelszo"></a>
  <h2>{lang text="JELSZO_CSEREJE"}</h2>
  <div class="subtitle">
    {lang text="JELSZO_CSEREJE_SUBTITLE"}
  </div>
  <div class="cont">
    {if $form}
      {$form->getMsg(1)}
    {/if}
    <form class="" action="/forms/user" method="post">
      <input type="hidden" name="return" value="{$smarty.const.CURRENT_PAGE}">
      <input type="hidden" name="form" value="1">
      <input type="hidden" name="for" value="settings_password">
      <div class="row">
        <div class="col-md-12 {if $form && $form->hasError(1, 'old')}input-error{/if}">
          <label for="old">{lang text="REGI_JELSZO"}*</label>
          <input type="password" name="data[old]" id="old" autocomplete="off" class="form-control">
        </div>
        <div class="divider"></div>
        <div class="col-md-12 {if $form && $form->hasError(1, 'new')}input-error{/if}">
          <label for="new">{lang text="UJ_JELSZO"}*</label>
          <input type="password" name="data[new]" id="new" autocomplete="off" class="form-control">
        </div>
        <div class="divider"></div>
        <div class="col-md-12 {if $form && $form->hasError(1, 'new2')}input-error{/if}">
          <label for="new2">{lang text="UJ_JELSZO_UJRA"}*</label>
          <input type="password" name="data[new2]" id="new2" autocomplete="off" class="form-control">
        </div>
        <div class="divider"></div>
        <div class="col-md-12">
          <button type="submit" class="btn btn-success">{lang text="MENTES"} <i class="fa fa-save"></i></button>
        </div>
      </div>
    </form>
  </div>
</div>
