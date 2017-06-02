<div class="select-list">
  <div class="value-viewer">
    <input type="text" class="form-control viewer" class="tglwatcher" tglwatcher="{$key}_select" placeholder="{lang text='Kérjük, válasszon!'}" readonly="readonly" value="[[selectedlist.{$key}.text]]">
    <input type="hidden" id="{$key}_select_id" name="{$key}" ng-model="form.{$key}" value="[[selectedlist.{$key}.id]]">
    <div class="helper">
      <i class="fa fa-angle-down"></i>
    </div>
  </div>
  <div class="single-selector-holder" id="{$key}_select">
    <div class="selector-wrapper">
      <div ng-class="'selector-row '+(selectedlist.{$key}.id == item.id ? 'selected' : '')" ng-repeat="item in terms.{$list}" ng-click="selectListValue('{$key}', item.id, item.value)" data-selectkey="{$key}" data-id="[[item.id]]">[[item.value]]</div>
    </div>
  </div>
</div>
