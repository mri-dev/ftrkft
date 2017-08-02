{include file=$template_root|cat:"user/parts/profil_progress.tpl"}

<div class="profil-changer-nav {if $me && $me->isMunkaado()}munkaado-line{/if}">
  <ul>
    <li class="ico-user {if $subprofil == 'alap'}active{/if}"><a href="/ugyfelkapu/profil"><div class="ico"></div>{lang text="Személyes adatok"}</a></li>
    <li class="ico-mobil {if $subprofil == 'elerhetoseg'}active{/if}"><a href="/ugyfelkapu/profil/elerhetoseg"><div class="ico"></div>{lang text="Elérhetőség"}</a></li>
    {if $me && $me->isMunkaado()}
    <li class="ico-idcard {if $subprofil == 'ceges'}active{/if}"><a href="/ugyfelkapu/profil/ceges"><div class="ico"></div>{lang text="Céges adatok"}</a></li>
    {/if}
    {if $me && $me->isUser()}
    <li class="ico-idcard {if $subprofil == 'vegzettseg'}active{/if}"><a href="/ugyfelkapu/profil/vegzettseg"><div class="ico"></div>{lang text="Végzettség Szakképzettség"}</a></li>
    {/if}
    {if $me && $me->isUser()}
    <li class="ico-document {if $subprofil == 'ismeretek'}active{/if}"><a href="/ugyfelkapu/profil/ismeretek"><div class="ico"></div>{lang text="Ismeretek"}</a></li>
    {/if}
    {if $me && $me->isUser()}
    <li class="ico-briefcase {if $subprofil == 'munkatapasztalat'}active{/if}"><a href="/ugyfelkapu/profil/munkatapasztalat"><div class="ico"></div>{lang text="Munkatapasztalat"}</a></li>
    {/if}
    {if $me && $me->isUser()}
    <li class="ico-target {if $subprofil == 'elvarasok'}active{/if}"><a href="/ugyfelkapu/profil/elvarasok"><div class="ico"></div>{lang text="Elvárások"}</a></li>
    {/if}
    <li class="ico-download {if $subprofil == 'dokumentumok'}active{/if}"><a href="/ugyfelkapu/profil/dokumentumok"><div class="ico"></div>{lang text="Dokumentum feltöltés"}</a></li>
  </ul>
</div>

<div class="profil-changer-container" ng-app="profilModifier" ng-controller="formValidor" ng-init="settings('{$subprofil}')">
  <div ng-show="!dataloaded" class="md-progress a-center">
    <div layout="row" layout-sm="column" layout-align="space-around">
      <md-progress-circular class="md-accent" md-diameter="50" md-mode="indeterminate"></md-progress-circular>
    </div>
    {lang text="Profil adatok betöltése."}
  </div>
  <div ng-show="dataloaded">
    {include file=$template_root|cat:"user/ugyfelkapu/profil/"|cat:$subprofil|cat:".tpl"}
    <div class="buttons" ng-show="dataloaded && cansavenow">
      <div ng-show="docs_uploading" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Dokumentumok feltöltése folyamatban..."}
      </div>
      <div ng-show="profilimguploadprogress" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Profilkép feltöltése folyamatban..."}
      </div>
      <div ng-show="!docs_uploading && docs_uploaded" class="alert alert-success">
        <i class="fa fa-check-circle"></i> {lang text="Dokumentumok sikeresen feltöltve."}
      </div>
      <div ng-show="successfullsaved && !saveinprogress" class="alert alert-success">
        <i class="fa fa-check-circle"></i> {lang text="Sikeresen elmentette a változásokat."}
      </div>
      <div ng-show="saveinprogress" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Változások mentése folyamatban. Kis türelmét kérjük."}
      </div>
      <div ng-show="!dataloaded" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Adatok betöltése folyamatban."}
      </div>
      <button ng-show="!saveinprogress" class="btn btn-success" ng-click="save(false)">{lang text="Mentés"}</button>
      <button ng-show="!saveinprogress" class="btn btn-danger btn-redhigh" ng-click="save(true)">{lang text="Mentés és tovább"}</button>
      {if $me && $me->isUser()}
        <a target="_blank" href="{$settings.page_url}{$me->getCVUrl()}" class="btn btn-default pull-right">{lang text="Önéletrajz megtekintése"} <i class="fa fa-file-text-o"></i></a>
      {/if}
    </div>
  </div>
</div>
