<h1>Üzenetek</h1>
<div class="messanger" ng-app="AdminMessanger" ng-controller="MessagesList" ng-init="init('{$smarty.get.sub}', {if $smarty.get.sub == 'session'}true{else}false{/if}, {$admin->getID()}, '{$msgsession}')">
  <div class="wrapper">
    {if $smarty.get.sub != 'session'}
    <div class="filters">
      <form class="" action="/cp/messanger/outbox" method="get">
      <fieldset>
        <legend>Szűrők</legend>
        {assign var="filter_qry" value=http_build_query($filter_arr)}
        <div class="row">
          <div class="col-md-1">
            <label for="ad">Hirdetés ID</label>
            <input type="text" id="ad" name="ad" value="{$smarty.get.ad}" class="form-control">
          </div>
          <div class="col-md-1">
            <label for="byadmin">Admin ID <i class="fa fa-info-circle" data-toggle="tooltip" title="Az admin ID-je, aki indította a beszélgetést."></i></label>
            <input type="text" id="byadmin" name="byadmin" value="{$smarty.get.byadmin}" class="form-control">
          </div>
          <div class="col-md-1">
            <label for="touser">Felh. ID <i class="fa fa-info-circle" data-toggle="tooltip" title="A felhasználó ID-ja, akinek címezve lett az üzenet."></i></label>
            <input type="text" id="touser" name="touser" value="{$smarty.get.touser}" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="toemail">Címzett e-mail vagy név</label>
            <input type="text" id="toemail" name="toemail" value="{$smarty.get.toemail}" class="form-control">
          </div>
          <div class="col-md-6">
            <button class="btn btn-default btn-sm" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </fieldset>
      </form>
    </div>
    {/if}

    <nav>
      <ul>
        {if $smarty.get.sub == 'session'}
        <li class="active"><a href="/ugyfelkapu/uzenetek/msg/{$smarty.get.msgid}" data-toggle="tooltip" title="[[messages['{$msgsession}'].subject]]"><i class="fa fa-eye"></i> [[messages['{$msgsession}'].from.name]]</a></li>
        {/if}
        <li class="{if $smarty.get.sub == 'outbox'}active{/if}"><a href="{$root}messanger/outbox"><i class="fa fa-envelope-open"></i> {lang text="Ajánlat céljából nyitott üzenetek"} {if $messangerinfo.outbox_unreaded != 0}
          <span class="badge">{$messangerinfo.outbox_unreaded}</span>
        {/if}</a></li>
        <li class="{if $smarty.get.sub == 'inbox'}active{/if}"><a href="{$root}messanger/inbox"><i class="fa fa-inbox"></i> {lang text="Beérkezett üzenetek"} {if $messangerinfo.inbox_unreaded != 0}
          <span class="badge">{$messangerinfo.inbox_unreaded}</span>
        {/if}</a></li>
        <li class="{if $smarty.get.sub == 'archiv'}active{/if}"><a href="{$root}messanger/archiv"><i class="fa fa-archive"></i> {lang text="Arhivált üzenetek"}</a></li>
      </ul>
    </nav>
    <br>

    {if $smarty.get.sub == 'session'}
      {include file=$template_root|cat:"messanger/msg.tpl"}
    {else}
      {include file=$template_root|cat:"messanger/list.tpl"}
    {/if}
  </div>
</div>
