<div class="row input-line">
  <div class="col-md-3">
    <label>{lang text="Mobil telefonszám"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="tel" class="form-control" ng-model="form.telefon">
      <div ng-class="'form-helper ' + ( (form.telefon) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
