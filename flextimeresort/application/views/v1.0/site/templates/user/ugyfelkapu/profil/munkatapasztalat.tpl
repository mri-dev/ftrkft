<div class="group">
  <div class="title">
    <h3><i class="fa fa-briefcase"></i> {lang text="Munkatapasztalatai"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Munkatapasztalata"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('munkatapasztalat', 'munkatapasztalat')}
        <div ng-class="'form-helper ' + ( (form.munkatapasztalat) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-handshake-o"></i> {lang text="Munkatapasztalatok, korábbi munkahelyek"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="munkatapasztalat" item="munkatapasztalat" mpkey="munkatapasztalat"></profil-modul>
</div>
