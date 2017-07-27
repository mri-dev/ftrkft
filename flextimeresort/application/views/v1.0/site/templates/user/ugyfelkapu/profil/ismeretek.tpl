<div class="group">
  <div class="title">
    <h3><i class="fa fa-id-card-o"></i> {lang text="Jogosítványok"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-12">
      <div class="input-wrapper">
        {$formdesigns->multiCheckbox('jogositvanyok', 'jogositvanyok')}
      </div>
    </div>
  </div>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-keyboard-o"></i> {lang text="Számítógépes ismeretek"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="ismeretek" item="szamitogepes" mpkey="szamitogepes"></profil-modul>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-language"></i> {lang text="Nyelvismeret"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="ismeretek" item="nyelvismeret" mpkey="nyelvismeret"></profil-modul>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-bars"></i> {lang text="Egyéb"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label for="ismeretek_ismeretek_egyeb">{lang text="Ismeretek részletei"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <textarea id="ismeretek_ismeretek_egyeb" class="form-control" rows="5" ng-model="form.ismeretek_egyeb"></textarea>
        <div class="form-helper"></div>
      </div>
    </div>
  </div>
</div>
