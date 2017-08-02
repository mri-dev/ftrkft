<div class="multipanel-selector-holder">
  <div class="collected">
    <div class="header">
      {lang text="Kiválasztott munkakörök"}:
    </div>
    <div ng-show="(collected_elvarasmunkakorok.length != 0) ? true : false">
      <div class="items">
        <div class="item" ng-repeat="c in collected_elvarasmunkakorok">
          [[c.text]]
        </div>
      </div>
    </div>
    <div ng-show="(collected_elvarasmunkakorok.length == 0) ? true : false">
      <div class="no-items">
          {lang text="Nincs kiválasztott munkakör."}
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="panel-main">
      <div class="header">
        {lang text="Fő területek"}:
      </div>
      <div ng-if="m.deep == 0" ng-class="(m.id == elvarasmunkakor.selectedmain)?'active':''" ng-click="elvarasmunkakor.selectedmain=m.id" data-parent="[[m.parent]]" data-id="[[m.id]]" data-deep="[[m.deep]]" ng-repeat="m in terms.munkakorok">
        [[m.value]]
      </div>
    </div>
    <div class="panel-sec" ng-show="elvarasmunkakor.selectedmain">
      <div class="header">
        {lang text="Fő terület szakterületei"}:
      </div>
      <div ng-if="m.deep == 1 && m.parent == elvarasmunkakor.selectedmain" ng-class="(elvarasmunkakor_picked.indexOf(m.id) !== -1)?'active':''" data-parent="[[m.parent]]" data-id="[[m.id]]" data-deep="[[m.deep]]" ng-click="collectElvarasMunkakor(m.parent, m.id)" ng-repeat="m in terms.munkakorok">
        [[m.value.substr(1)]]
      </div>
    </div>
  </div>
</div>
