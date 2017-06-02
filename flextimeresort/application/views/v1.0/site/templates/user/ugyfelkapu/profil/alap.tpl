<div class="group">
  <div class="title">
    <h3><i class="fa fa-user"></i> {lang text="Személyes adatok megadása"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label for="default_name">{lang text="NEV_CEGNEV"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <input type="text" id="default_name" class="form-control" required="required" ng-model="form.name">
      </div>
    </div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label for="default_nem">{lang text="Nem"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('nem', 'nem')}
      </div>
    </div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label for="default_email">{lang text="EMAIL_ADDRESS"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <input type="email" id="default_email" class="form-control" required="required" ng-model="form.email">
      </div>
    </div>
  </div>
</div>
