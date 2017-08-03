<div class="ertesito-kozpont">
  {if $alerts->Count() != 0}
    {while $alerts->walk()}
    {assign var="button" value=$alerts->getNavButton()}
    <div class="item status-{if $alerts->isWatched()}watched{else}unwatched{/if}">
      <div class="ico">
        <i class="fa fa-{$alerts->getIcon()}"></i>
      </div>
      <div class="data">
        <div class="wrapper">
          <div class="message">
            {$alerts->getMessage()}
            {if $button}
            <div class="item-msg">
              {$button.msg}
            </div>
            {/if}
          </div>
          <div class="infos">
            <div class="date">
              <span>{$alerts->getAlertDate()}</span>
            </div>
            {if $button}
            <div class="button">
              <a class="btn btn-sm btn-default" href="{$button.url}">{$button.text}</a>
            </div>
            {/if}
          </div>
        </div>
      </div>
    </div>
    {/while}
  {else}
    <div class="no-alerts">
      <i class="fa fa-bullhorn"></i>
      <h2>{lang text="Nincs értesítés"}</h2>
      {lang text="Önnek nincsennek értesítései a fiókjával kapcsolatba."}
    </div>
  {/if}
</div>
