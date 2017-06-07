{if !$hide_megye_list}
<div class="megye-list">
  <div class="head">
    {lang text="Találatok megyék szerint"}
  </div>
  <div class="fakemap">
    <img src="{$smarty.const.IMG}hungary-siluette.svg" alt="Map">
  </div>
  <div class="cont">
    <div class="list">
      {foreach from=$megyelist item=megye}
      <div>
        <a href="{$settings.allas_search_slug}?megye={$megye.id}">
          <div class="text">{$megye.neve}</div>
          <div class="count">{$megye.count}</div>
        </a>
      </div>
      {/foreach}
    </div>
  </div>
</div>
{/if}
