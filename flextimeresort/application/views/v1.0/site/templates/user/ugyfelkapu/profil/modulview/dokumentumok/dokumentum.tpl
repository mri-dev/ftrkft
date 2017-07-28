<div class="row">
  <div class="col-md-12">
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in multiparam[mpkey]">
        <input type="file" class="hide" id="profil" document-uploader="profil">
        <label for="profil" data-toggle="tooltip" title="{lang text='Válassza ki a feltöltendő dokumentumot'}">{lang text="Fájl kiválasztása"}</label>

        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Dokumentum elnevezése, leírása"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="multiparam[mpkey][mpi].megnevezes" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-12">
            <button type="button" ng-click="modulVariableRemover(mpkey, mpi, multiparam[mpkey][mpi].grouphash)" class="btn btn-danger btn-sm" name="button">{lang text="Rekord törlése"} <i class="fa fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" ng-click="newModulVariable(mpkey)" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {lang text="Új hozzáadása"}</button>
  </div>
</div>
