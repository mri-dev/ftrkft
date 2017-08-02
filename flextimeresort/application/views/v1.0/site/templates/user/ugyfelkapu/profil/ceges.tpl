<div class="group">
  <div class="title">
    <h3><i class="fa fa-building"></i> {lang text="Cég adatai"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label for="ceges_alapitas_ev">{lang text="Alapítás éve"}</label>
    </div>
    <div class="col-md-3">
      <div class="input-wrapper">
        <input type="number" id="ceges_alapitas_ev" class="form-control" ng-model="form.ceges_alapitas_ev">
        <div class="form-helper"></div>
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label for="ceges_foglalkoztatottak_szama">{lang text="Foglalkoztatottak száma"}</label>
    </div>
    <div class="col-md-3">
      <div class="input-wrapper">
        <input type="number" id="ceges_foglalkoztatottak_szama" class="form-control" ng-model="form.ceges_foglalkoztatottak_szama">
        <div class="form-helper"></div>
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Melyik megyékbe van jelen?"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->multiCheckbox('ceges_megyek', 'megyek',['cols' => 2])}
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Mely területeken érdekelt a cég?"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->multiCheckbox('ceges_munkateruletek', 'munkakorok',['cols' => 1, 'deepfilter' => 0])}
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Érdekelt munkakörök"}:<br><small>{lang text="Érdekelt munkakörök_TEXT"}</small> </label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <profil-modul group="ceges" item="munkakorselector" mpkey="munkakorselector"></profil-modul>
      </div>
    </div>
  </div>
</div>
