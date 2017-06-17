<div class="allas-list" ng-app="Ads" ng-controller="Listing" ng-init="init()">
  <div ng-show="!loaded" class="alert alert-warning">
    <i class="fa fa-spin fa-spinner"></i> {lang text="Feltöltött állások betöltése folyamatban..."}
  </div>
  <div ng-show="loaded">
    <div class="header">
      <div class="row">
        <div class="col-md-6">
          <h2>[[allas_db]] {lang text="db"} {lang text="feltöltött hirdetés"}</h2>
        </div>
        <div class="col-md-6 right">
          <a href="/ugyfelkapu/uj-hirdetesek" class="btn btn-danger btn-sm">{lang text="Új hirdetés"} <i class="fa fa-plus-circle"></i></a>
        </div>
      </div>
    </div>
    <div class="allasok">
      <div class="allas" ng-repeat="allas in allasok">
        <div class="edit">
          <a href="/ugyfelkapu/hirdetesek/mod/[[allas.ID]]" data-toggle="tooltip" title="{lang text='Hirdetés szerkesztése'}"><i class="fa fa-pencil"></i></a>
        </div>
        <div class="datarow">
          <span class="type" data-toggle="tooltip" title="{lang text='Hirdetés típusa'}">[[allas.tipus_name]]</span>
          <span class="cat" data-toggle="tooltip" title="{lang text='Hirdetés kategóriája'}">[[allas.cat_name]]</span>
          <span class="city">[[allas.city]]</span>
        </div>
        <div class="shortdesc">
          [[allas.short_desc]]
        </div>
      </div>
    </div>
  </div>
</div>
