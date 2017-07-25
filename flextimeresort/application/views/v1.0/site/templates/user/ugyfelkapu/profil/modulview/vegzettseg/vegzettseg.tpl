<div class="row">
  <div class="col-md-12">
    <div class="multi-param-holder">
      <div class="multi-param" ng-repeat="(mpi, mp) in multiparam[multiparam_key]">
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Végzettség szintje"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control">
              <option value="[[szint.id]]" ng-repeat="szint in terms.iskolai_vegzettsegi_szintek">[[szint.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Terület/Szakirányzat"}</label>
          </div>
          <div class="col-md-8">
            <select class="form-control">
              <option value="[[terszak.id]]" ng-repeat="terszak in terms.tanulmany_szakirany">[[terszak.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Intézmény neve"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Végzettség készségei"}</label>
          </div>
          <div class="col-md-8">
            <textarea class="form-control"></textarea>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Folyamatban"}</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Kezdés időpont"}</label>
          </div>
          <div class="col-md-4">
            <select class="form-control">
              <option value="[[year]]" ng-repeat="year in yearGenerator()">[[year]]</option>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-control">
              <option value="[[honap.id]]" ng-repeat="honap in terms.honapok">[[honap.value]]</option>
            </select>
          </div>
        </div>
        <div class="row input-line">
          <div class="col-md-4">
            <label for="">{lang text="Befejezés időpont"}</label>
          </div>
          <div class="col-md-4">
            <select class="form-control">
              <option value="[[year]]" ng-repeat="year in yearGenerator()">[[year]]</option>
            </select>
          </div>
          <div class="col-md-4">
            <select class="form-control">
              <option value="[[honap.id]]" ng-repeat="honap in terms.honapok">[[honap.value]]</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <button type="button" ng-click="newModulVariable(multiparam_key)" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {lang text="Új hozzáadása"}</button>
  </div>
</div>
