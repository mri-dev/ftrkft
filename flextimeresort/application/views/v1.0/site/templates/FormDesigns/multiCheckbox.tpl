<div class="checkbox-list cols{$cols}">
  <div class="cb-item" ng-repeat="item in terms.{$list}" ng-if="{if isset($deepfilter)}item.deep == {$deepfilter}{else}true{/if}">
    <input type="checkbox" id="{$list}_cb_[[item.id]]" ng-checked="(form.{$key} && form.{$key}.indexOf(item.id) !== -1)?true:false" ng-click="collectCheckboxData('{$key}', item.id)" ng-value="item.id" class="ccb"> <label for="{$list}_cb_[[item.id]]">[[item.value]]</label>
  </div>
</div>
