{include file=$template_root|cat:"user/parts/profil_progress.tpl"}

<div class="profil-changer-nav">
  <ul>
    <li class="ico-user {if $subprofil == 'alap'}active{/if}"><a href="/ugyfelkapu/profil"><div class="ico"></div>{lang text="Személyes adatok"}</a></li>
    <li class="ico-mobil {if $subprofil == 'elerhetoseg'}active{/if}"><a href="/ugyfelkapu/profil/elerhetoseg"><div class="ico"></div>{lang text="Elérhetőség"}</a></li>
    <li class="ico-idcard {if $subprofil == 'vegzettseg'}active{/if}"><a href="/ugyfelkapu/profil/vegzettseg"><div class="ico"></div>{lang text="Végzettség Szakképzettség"}</a></li>
    <li class="ico-document {if $subprofil == 'ismeretek'}active{/if}"><a href="/ugyfelkapu/profil/ismeretek"><div class="ico"></div>{lang text="Ismeretek"}</a></li>
    <li class="ico-briefcase {if $subprofil == 'munkatapasztalat'}active{/if}"><a href="/ugyfelkapu/profil/munkatapasztalat"><div class="ico"></div>{lang text="Munkatapasztalat"}</a></li>
    <li class="ico-target {if $subprofil == 'elvarasok'}active{/if}"><a href="/ugyfelkapu/profil/elvarasok"><div class="ico"></div>{lang text="Elvárások"}</a></li>
    <li class="ico-download {if $subprofil == 'dokumentumok'}active{/if}"><a href="/ugyfelkapu/profil/dokumentumok"><div class="ico"></div>{lang text="Dokumentum feltöltés"}</a></li>
  </ul>
</div>

<div class="profil-changer-container" ng-app="profilModifier" ng-controller="formValidor" ng-init="settings('{$subprofil}')">
  {include file=$template_root|cat:"user/ugyfelkapu/profil/"|cat:$subprofil|cat:".tpl"}
  <div class="buttons" ng-show="dataloaded && cansavenow">
    <button class="btn btn-success" ng-click="save(false)">{lang text="Mentés"}</button>
    <button class="btn btn-danger btn-redhigh" ng-click="save(true)">{lang text="Mentés és tovább"}</button>
  </div>
</div>
