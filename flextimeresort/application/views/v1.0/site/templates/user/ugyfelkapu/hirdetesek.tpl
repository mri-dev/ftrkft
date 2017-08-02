<div class="allas-list" ng-app="Ads" ng-controller="Listing" ng-init="init()">
  {if isset($smarty.get.successUserRequest)}
  <div class="alert alert-success">
    {lang text="Gratulálunk. Munkavállalók adatainak lekérését sikeresen kérvényezte."}
  </div>
  {/if}
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
      <div class="allas" ng-if="{if !empty($smarty.get.hlad)}allas.ID == {$smarty.get.hlad}{else}true{/if}" ng-repeat="allas in allasok">
        <div class="datarow">
          <span class="type" ng-show="allas.tipus_name" data-toggle="tooltip" title="{lang text='Hirdetés típusa'}"><i class="fa fa-database"></i>  [[allas.tipus_name]]</span>
          <span class="cat" ng-show="allas.cat_name" data-toggle="tooltip" title="{lang text='Hirdetés kategóriája'}"><i class="fa fa-user"></i> [[allas.cat_name]]</span>
          <span class="city"><i class="fa fa-map-marker"></i> [[allas.city]]</span>
          <div class="edit">
            <a href="/ugyfelkapu/hirdetesek/mod/[[allas.ID]]" data-toggle="tooltip" title="{lang text='Hirdetés szerkesztése'}"><i class="fa fa-pencil"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="shortdesc">
          [[allas.short_desc]]
        </div>
        <div class="afterinfo">
          <span class="status">
            <span class="status-aktiv" ng-show="(allas.active == '1')?true:false"><i class="fa fa-eye"></i> {lang text="Aktív"}</span>
            <span class="status-inaktiv" ng-show="(allas.active == '0')?true:false"><i class="fa fa-eye-slash"></i> {lang text="Inaktív"}</span>
          </span>
          <span class="time" data-toggle="tooltip" title="{lang text='Hirdetés közzététel ideje'}"><i class="fa fa-clock-o"></i> [[allas.publish_after]]</span>
        </div>
      </div>
    </div>
  </div>
</div>
