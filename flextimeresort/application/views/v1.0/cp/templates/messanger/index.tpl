<div class="messanger" ng-app="AdminMessanger" ng-controller="MessagesList" ng-init="init('{$smarty.get.sub}', {if $smarty.get.sub == 'session'}true{else}false{/if}, {$me->getID()}, '{$msgsession}')">
  <div class="wrapper">

    {if $smarty.get.sub == 'session'}
      {include file=$template_root|cat:"messanger/msg.tpl"}
    {else}
      {include file=$template_root|cat:"messanger/list.tpl"}
    {/if}
  </div>
</div>
