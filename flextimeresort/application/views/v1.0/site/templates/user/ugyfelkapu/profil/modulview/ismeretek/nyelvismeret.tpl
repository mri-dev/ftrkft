<div class="row">
  <div class="col-md-12">
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in multiparam[mpkey]">
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Nyelv"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].nyelv">
              <option ng-value="[[nyelv.id]]" ng-repeat="nyelv in terms.nyelvek">[[nyelv.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Szóbeli készség szintje"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].szobeli_szint">
              <option ng-value="[[nyelvi.id]]" ng-repeat="nyelvi in terms.nyelvismeret">[[nyelvi.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Írásbeli készség szintje"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control" ng-model="multiparam[mpkey][mpi].irasbeli_szint">
              <option ng-value="[[nyelvi.id]]" ng-repeat="nyelvi in terms.nyelvismeret">[[nyelvi.value]]</option>
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
