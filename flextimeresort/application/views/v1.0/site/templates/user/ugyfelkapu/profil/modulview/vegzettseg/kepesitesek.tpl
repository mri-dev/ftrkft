<div class="row">
  <div class="col-md-12">
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in multiparam[mpkey]">
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Megnevezés"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="multiparam[mpkey][mpi].megnevezes" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Intézmény neve"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" ng-model="multiparam[mpkey][mpi].intezmeny" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Végzettség készségei"}</label>
          </div>
          <div class="col-md-8">
            <textarea class="form-control" ng-model="multiparam[mpkey][mpi].keszsegek"></textarea>
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
          <div class="col-md-12">
            <button type="button" ng-click="modulVariableRemover(mpkey, mpi, multiparam[mpkey][mpi].grouphash)" class="btn btn-danger btn-sm" name="button">{lang text="Rekord törlése"} <i class="fa fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" ng-click="newModulVariable(mpkey)" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {lang text="Új hozzáadása"}</button>
  </div>
</div>
