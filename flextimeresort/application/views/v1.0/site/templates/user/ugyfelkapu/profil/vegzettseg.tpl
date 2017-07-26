<div class="group">
  <div class="title">
    <h3><i class="fa fa-gavel"></i> {lang text="Képzettségek"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Legmagasabb iskolai végzettség"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        {$formdesigns->singleSelector('iskolai_vegzettsegi_szintek', 'iskolai_vegzettsegi_szintek')}
        <div ng-class="'form-helper ' + ( (form.iskolai_vegzettsegi_szintek) ? 'valid' : 'invalid')"></div>
      </div>
    </div>
  </div>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-graduation-cap"></i> {lang text="Végzettségek"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="vegzettseg" item="vegzettseg" mpkey="vegzettseg"></profil-modul>
</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-flag-o"></i> {lang text="További képzettség, tréning, tanfolyam"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="vegzettseg" item="kepesitesek" mpkey="kepesitesek"></profil-modul>
</div>
