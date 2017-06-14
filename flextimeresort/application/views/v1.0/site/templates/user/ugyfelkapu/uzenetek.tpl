<div class="messanger" ng-app="UserMessanger" ng-controller="MessagesList" ng-init="init('{$smarty.get.sub}', {if $smarty.get.sub == 'msg'}true{else}false{/if}, {$me->getID()}, '{$msgsession}')">
  <div class="wrapper">
    <nav>
      <ul>
        {if $smarty.get.sub == 'msg'}
        <li class="active"><a href="/ugyfelkapu/uzenetek/msg/{$smarty.get.msgid}" data-toggle="tooltip" title="[[messages['{$msgsession}'].subject]]"><i class="fa fa-eye"></i> [[messages['{$msgsession}'].from.name]]</a></li>
        {/if}
        <li class="{if $smarty.get.sub == 'inbox'}active{/if}"><a href="/ugyfelkapu/uzenetek/inbox"><i class="fa fa-inbox"></i> {lang text="Beérkezett üzenetek"} {if $messangerinfo.inbox_unreaded != 0}
          <span class="badge">{$messangerinfo.inbox_unreaded}</span>
        {/if}</a></li>
        <li class="{if $smarty.get.sub == 'outbox'}active{/if}"><a href="/ugyfelkapu/uzenetek/outbox"><i class="fa fa-envelope-open"></i> {lang text="Elküldött üzenetek"} {if $messangerinfo.outbox_unreaded != 0}
          <span class="badge">{$messangerinfo.outbox_unreaded}</span>
        {/if}</a></li>
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
