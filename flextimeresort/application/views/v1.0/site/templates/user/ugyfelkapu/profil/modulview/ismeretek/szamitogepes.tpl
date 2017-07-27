<div class="row">
  <div class="col-md-12">
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in multiparam[mpkey]">
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Szakterület"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].szamitastechnikai_ismeret">
              <option ng-value="[[szi.id]]" ng-repeat="szi in terms.szamitastechnikai_ismeretek">[[szi.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Tudásszint"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].tudasszint">
              <option ng-value="[[tu.id]]" ng-repeat="tu in terms.tudasszintek">[[tu.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Tapasztalat (év)"}</label>
          </div>
          <div class="col-md-2">
            <input type="number" ng-model="multiparam[mpkey][mpi].tapasztalat_ev" class="form-control">
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
