<div class="row">
  <div class="col-md-12">
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in multiparam[mpkey]">

        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Szakterület/Munkakör"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].munkakor">
              <option ng-value="[[m.id]]" ng-repeat="m in terms.munkakorok">[[m.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Beosztás megnevezése"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="multiparam[mpkey][mpi].beosztas" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Kezdés időpont"}</label>
          </div>
          <div class="col-md-4">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].startdate.year" >
              <option ng-value="[[year]]" ng-repeat="year in yearGenerator()">[[year]]</option>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].startdate.month" >
              <option ng-value="[[honap.sort]]" ng-repeat="honap in terms.honapok">[[honap.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line" style="margin-top: 20px; margin-bottom: 20px;">
          <div class="col-md-4">
            <label for="folyamatban[[mpi]]">{lang text="Folyamatban"}</label>
          </div>
          <div class="col-md-8">
          <md-switch class="md-primary" id="folyamatban[[mpi]]" ng-model="multiparam[mpkey][mpi].folyamatban" ></md-switch>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Befejezés időpont"}</label>
          </div>
          <div class="col-md-4">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].enddate.year">
              <option ng-value="[[year]]" ng-repeat="year in yearGenerator()">[[year]]</option>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].enddate.month">
              <option ng-value="[[honap.sort]]" ng-repeat="honap in terms.honapok">[[honap.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Cég neve"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="multiparam[mpkey][mpi].ceg_neve" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Munkavégzés helye"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="multiparam[mpkey][mpi].munkavegzes_helye" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Beosztás szintje"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].beosztasi_szint">
              <option ng-value="[[b.id]]" ng-repeat="b in terms.beosztasi_szint">[[b.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Beosztásban végzett feladatok"}</label>
          </div>
          <div class="col-md-8">
            <textarea ng-model="multiparam[mpkey][mpi].feladatok" class="form-control"></textarea>
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
