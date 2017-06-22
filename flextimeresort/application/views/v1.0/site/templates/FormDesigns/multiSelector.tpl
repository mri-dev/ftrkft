<div class="select-list">
  <div class="value-viewer">
    <input type="text" class="form-control viewer" ng-click="tglList('{$key}')" placeholder="{lang text='Kérjük, válasszon!'}" readonly="readonly" value="[[selectedlist.{$key}.texts.join(', ')]]">
    <div class="helper">
      <i class="fa fa-angle-down"></i>
    </div>
  </div>
  <div class="single-selector-holder" ng-show="listtgl.{$key}">
    <div class="selector-wrapper">
      <div ng-class="'selector-row '+(selectedlist.{$key}.ids && selectedlist.{$key}.ids.indexOf(item.id) != -1 ? 'selected' : '')" ng-click="selectListValue('{$key}', item.id, item.value, true)" data-listindex="[[index]]" ng-repeat="item in terms.{$key}">[[item.value]]</div>
    </div>
  </div>
</div>
