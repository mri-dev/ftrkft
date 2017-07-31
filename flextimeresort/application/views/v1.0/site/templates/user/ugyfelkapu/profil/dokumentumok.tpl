<div class="group">
  <div class="title">
    <h3><i class="fa fa-file-text-o"></i> {lang text="Önéletrajz"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <div class="row input-line">
    <div class="col-md-3">
      <label>{lang text="Külső link"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <input type="text" class="form-control" ng-model="form.kulso_oneletrajz_url">
      </div>
    </div>
  </div>
  <div class="row input-line" style="align-items:flex-start;">
    <div class="col-md-3">
      <label>{lang text="Önéletrajz feltöltése"}</label>
    </div>
    <div class="col-md-9">
      <div class="input-wrapper">
        <div class="uploaded-file" ng-show="oneletrajz.name">
          <a href="[[oneletrajz.filepath]]"><i class="fa fa-file"></i> <strong>[[oneletrajz.name]]</strong> ([[oneletrajz.file_size]] Kb., [[oneletrajz.file_type]])</a>
        </div>
        <input type="file" class="hide" id="oneletrajz" document-uploader root="oneletrajz">
        <label class="file-uploader btn btn-primary" for="oneletrajz" data-toggle="tooltip" title="{lang text='Válassza ki a feltöltendő dokumentumot'}">{lang text="Fájl kiválasztása"} <i class="fa fa-folder-o"></i></label>
        <div class="clearfix"></div>
        <div ng-show="files.oneletrajz" class="file-info">
          <div>{lang text="Fájlnév"}: <strong>[[files.oneletrajz.name]]</strong></div>
          <div ng-class="(!files.oneletrajz.correct_ext)?'invalid':'valid'">{lang text="Fájltípus"}: <strong>[[files.oneletrajz.ext]]</strong></div>
          <div ng-class="(!files.oneletrajz.correct_filesize)?'invalid':'valid'">{lang text="Fájlméret"}: <strong>[[files.oneletrajz.size]] Kb</strong></div>
          <div ng-class="(!files.oneletrajz.canuploadnow)?'invalid':'valid'">{lang text="Státusz"}: <strong>[[(files.oneletrajz.canuploadnow)?'{lang text="Megfelelő fájl"}':'{lang text="Nem megfelelő fájl"}']]</strong></div>
        </div>
        <small>{lang text="Önéletrajz fájl leírás"}</small>
      </div>
    </div>
  </div>

</div>
<div class="group">
  <div class="title">
    <h3><i class="fa fa-files-o"></i> {lang text="További dokumentumok"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="dokumentumok" item="dokumentum" mpkey="dokumentum"></profil-modul>
</div>
