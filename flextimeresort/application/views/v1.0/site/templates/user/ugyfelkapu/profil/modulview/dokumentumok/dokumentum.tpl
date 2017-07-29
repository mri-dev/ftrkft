<div class="row">
  <div class="col-md-12">
    <div class="uploaded-documents" ng-show="documents.length!=0">
      <div class="header">
        {lang text="Feltöltött dokumentumok"}
      </div>
      <div class="document" ng-class="(d.delettingnow)?'deleting':''" ng-repeat="(i,d) in documents">
        <div class="row">
          <div class="col-md-10">
            <a href="[[d.filepath]]">[[d.name]]</a>
            <div class="infos">
              <span class="size">{lang text="Fájlméret"}: <strong>[[d.file_size]]</strong></span>
              <span class="type">{lang text="Fájltípus"}: <strong>[[d.file_type]]</strong></span>
              <span class="date">{lang text="Feltöltve"}: <strong>[[d.upload_at]]</strong></span>
            </div>
          </div>
          <div class="col-md-2">
            <button ng-show="!d.delettingnow" ng-click="uploadedDocumentRemover(i, d.hashkey)" class="btn btn-sm btn-danger pull-right">{lang text="törlés"}<i class="fa fa-times"></i></button>
            <span class="pull-right deleteprogress" ng-show="d.delettingnow">{lang text="Törlés"} <i class="fa fa-spin fa-spinner"></i></span>
          </div>
        </div>
      </div>
    </div>
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in files[mpkey]">
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Dokumentum elnevezése, leírása"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="files[mpkey][mpi].name" class="form-control">
          </div>
        </div>
        <div class="row input-line" style="align-items:flex-start;">
          <div class="col-md-4">
            <label for="">{lang text="Dokumentum"}</label>
          </div>
          <div class="col-md-8">
            <input type="file" class="hide" id="[[mpkey]][[mpi]]" document-uploader root="[[mpkey]]" docindex="[[mpi]]">
            <label class="file-uploader btn btn-primary" for="[[mpkey]][[mpi]]" data-toggle="tooltip" title="{lang text='Válassza ki a feltöltendő dokumentumot'}">{lang text="Fájl kiválasztása"}  <i class="fa fa-folder-o"></i></label>
            <div class="clearfix"></div>
            <div ng-show="files[mpkey][mpi].raw" class="file-info">
              <div>{lang text="Fájlnév"}: <strong>[[files[mpkey][mpi].name]]</strong></div>
              <div ng-class="(!files[mpkey][mpi].correct_ext)?'invalid':'valid'">{lang text="Fájltípus"}: <strong>[[files[mpkey][mpi].ext]]</strong></div>
              <div ng-class="(!files[mpkey][mpi].correct_filesize)?'invalid':'valid'">{lang text="Fájlméret"}: <strong>[[files[mpkey][mpi].size]] Kb</strong></div>
              <div ng-class="(!files[mpkey][mpi].canuploadnow)?'invalid':'valid'">{lang text="Státusz"}: <strong>[[(files[mpkey][mpi].canuploadnow)?'{lang text="Megfelelő fájl"}':'{lang text="Nem megfelelő fájl"}']]</strong></div>
            </div>
            <small>{lang text="Önéletrajz fájl leírás"}</small>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-12">
            <button type="button" ng-click="fileItemRemover(mpkey, mpi)" class="btn btn-danger btn-sm" name="button">{lang text="Mégse"} <i class="fa fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" ng-click="fileItemAdder(mpkey)" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {lang text="Új hozzáadása"}</button>
  </div>
</div>
