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
        <div class="form-helper"></div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Nem"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('nem', 'nem')}
        <div ng-class="'form-helper ' + ( (form.nem) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Állampolgárság"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('allampolgarsag', 'allampolgarsag')}
        <div ng-class="'form-helper ' + ( (form.allampolgarsag) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Anyanyelv"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('anyanyelv', 'anyanyelv')}
        <div ng-class="'form-helper ' + ( (form.anyanyelv) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Családi állapot"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('csaladi_allapot', 'csaladi_allapot')}
        <div ng-class="'form-helper ' + ( (form.csaladi_allapot) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label for="default_email">{lang text="EMAIL_ADDRESS"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <input type="email" id="default_email" class="form-control" required="required" readonly="readonly" ng-model="form.email">
        <div class="form-helper"></div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label for="default_birthday">{lang text="Születési dátum"} *</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <input type="text" id="default_birthday" ng-focus="szuletesi_datum_focus=true" ng-blur="szuletesi_datum_focus=false" class="form-control" ng-model="form.szuletesi_datum" required="required" ng-pattern="/^\d{'{4}'}-\d{'{2}'}-\d{'{2}'}$/">
        <div class="form-helper"></div>
        <div class="infotext" ng-style="(szuletesi_datum_focus==true ? texthintfocusstyle : '')">
          {lang text="Elvárt dátum formátum: %date%" date=$smarty.now|date_format:"%F"}
        </div>
      </div>
    </div>
  </div>

  <div class="row input-line">
    <div class="col-md-3">
      <label for="default_birthday">{lang text="Profilkép"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <input type="file" ng-model="profil" value="">
        <div class="form-helper"></div>
      </div>
    </div>
  </div>

</div>
