<div class="row input-line">
  <div class="col-md-3">
    <label>{lang text="Kapcsolattartó neve"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="text" class="form-control" ng-model="form.ceges_kapcsolat_nev">
      <div ng-class="'form-helper ' + ( (form.ceges_kapcsolat_nev) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
<div class="row input-line">
  <div class="col-md-3">
    <label>{lang text="Kapcsolattartó e-mail"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="email" class="form-control" ng-model="form.ceges_kapcsolat_email">
      <div ng-class="'form-helper ' + ( (form.ceges_kapcsolat_email) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
<div class="row input-line">
  <div class="col-md-3">
    <label>{lang text="Kapcsolattartó telefon"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="text" class="form-control" ng-model="form.ceges_kapcsolat_telefon">
      <div ng-class="'form-helper ' + ( (form.ceges_kapcsolat_telefon) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
