<div class="group">
  <div class="title">
    <h3><i class="fa fa-briefcase"></i> {lang text="Fizetési igény"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Bérigény (bruttó)"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <div class="input-group">
          <input type="number" class="form-control" min="0" step="1000" ng-model="form.fizetesi_igeny">
          <span class="input-group-addon">Ft</span>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-bullseye"></i> {lang text="Munkával kapcsolat igények"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Melyik megyékbe vállalna munkát?"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->multiCheckbox('megyeaholdolgozok', 'megyek',['cols' => 2])}
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Mely területek érdeklik?"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->multiCheckbox('elvaras_munkateruletek', 'munkakorok',['cols' => 1, 'deepfilter' => 0])}
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Betölteni kívánt munkakörök"}:<br><small>{lang text="Betölteni kívánt munkakörök_TEXT"}</small> </label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <profil-modul group="elvarasok" item="munkakorselector" mpkey="munkakorselector"></profil-modul>
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Egyéb betöltendő munkakörök"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <textarea class="form-control" ng-model="form.igenyek_egyeb_munkakorok"></textarea>
        <div ng-class="'form-helper ' + ( (form.igenyek_egyeb_munkakorok) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>

</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-hashtag"></i> {lang text="Egyéb igények, információk"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Kezdés ideje"}</label>
    </div>
    <div class="col-md-4">
      <div class="input-wrapper">
        <md-datepicker ng-model="form.munkaba_allas_ideje" md-placeholder="{lang text='Válasszon'}"></md-datepicker>
        <div ng-class="'form-helper ' + ( (form.munkaba_allas_ideje) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>
  <div class="divider lined"></div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="További, egyéb igényei"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <textarea class="form-control" ng-model="form.igenyek_egyeb"></textarea>
        <div ng-class="'form-helper ' + ( (form.igenyek_egyeb) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>

</div>
