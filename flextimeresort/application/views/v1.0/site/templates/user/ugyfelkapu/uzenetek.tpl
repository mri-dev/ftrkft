<div class="messanger" ng-app="UserMessanger" ng-controller="MessagesList" ng-init="init({if $smarty.get.sub == 'msg'}true{else}false{/if})">
  <div class="wrapper">
    <nav>
      <ul>
        <li class="{if $smarty.get.sub == 'inbox'}active{/if}"><a href="/ugyfelkapu/uzenetek/inbox"><i class="fa fa-inbox"></i> {lang text="Beérkezett üzenetek"} <span ng-show="unreaded_messages != 0 ? true : false" class="badge">[[unreaded_messages]]</span></a></li>
        <li class="{if $smarty.get.sub == 'outbox'}active{/if}"><a href="/ugyfelkapu/uzenetek/outbox"><i class="fa fa-envelope-open"></i> {lang text="Elküldött üzenetek"}</a></li>
        <li class="{if $smarty.get.sub == 'archiv'}active{/if}"><a href="/ugyfelkapu/uzenetek/archiv"><i class="fa fa-archive"></i> {lang text="Arhivált üzenetek"}</a></li>
      </ul>
    </nav>
    {if $smarty.get.sub == 'msg'}
      {include file=$template_root|cat:"user/ugyfelkapu/uzenetek/msg.tpl"}
    {else}
      {include file=$template_root|cat:"user/ugyfelkapu/uzenetek/list.tpl"}
    {/if}
  </div>
</div>
