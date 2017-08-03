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
        <div class="wrapper">
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
            <span data-toggle="tooltip" title="{lang text='Lista megtekintése'}" ng-if="allas.requestedUsers.total!=0" ng-click="requestUserShowToggler(allas.ID)" class="requested-users"><strong>[[allas.requestedUsers.total]] {lang text="db"}</strong> {lang text="igényelt munkavállaló"}</span>
          </div>
        </div>
        <div class="user-requests" ng-show="requestUserShow[allas.ID]{if isset($smarty.get.showUserRequests)} || true{/if}">
          <div class="list-wrapper">
            <h3>{lang text="Igényelt munkavállalók a hirdetéshez"}</h3>
            <div class="user gender[[u.user.gender.ID]]" ng-repeat="u in allas.requestedUsers.data">
              <div class="wrapper">
                <div class="profilimg">
                  <img src="[[u.user.profilimg]]" alt="[[u.user.name]]">
                </div>
                <div class="dataset">
                  <div class="name">
                    <a href="[[u.user.url]]" target="_blank">[[u.user.name]]</a>
                  </div>
                  <div class="szakma">
                    [[u.user.szakma]]
                  </div>
                  <div class="subline">
                    <span class="city">[[u.user.city]]</span>
                  </div>
                </div>
                <div class="status">
                  <div class="date">
                    {lang text="Felvéve"}: <strong>[[u.requested_at]]</strong>
                  </div>
                  <div class="user-decide">
                    {lang text="Felhasználó visszajelzés"}:
                    <strong>
                    <span ng-if="u.feedback == -1" class="inprogress">{lang text="Kapcsolatfelvétel alatt"}.</span>
                    <span ng-if="u.feedback == 0" class="declined">{lang text="Kapcsolat felvéve: ajánlat nem érdekli"}.</span>
                    <span ng-if="u.feedback == 1" class="accept">{lang text="Kapcsolat felvéve: ajánlat érdekli"}.</span>
                    </strong>
                  </div>
                  <div class="access-granted">
                    {lang text="Teljes hozzáférés megadva"}: <strong><span ng-if="u.access_granted" class="yes">{lang text="Igen"} ([[u.granted_date_at]])</span> <span  ng-if="!u.access_granted" class="no">{lang text="Nem"}</span></strong>
                  </div>
                  <div class="acess-date" ng-if="u.granted_date_at">
                    {lang text="Hozzáférés lejárati ideje"}: <strong>[[u.grant_date_expired]]</strong>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
