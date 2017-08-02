<aside class="sidebar user-{if $me && $me->isUser()}munkavallalo{else $me && $me->isMunkaado()}munkaado{/if}">
  {include file='inc/megyelist.tpl'}
  {include file='inc/sidebar_facebox.tpl'}
</aside>
