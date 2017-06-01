<div class="page-menu">
	<ul>
		<li class="{if $GETS[1] == ''}on{/if}"><a href="/user"><i class="fa fa-home"></i></a></li>
		<li class="{if $GETS[1] == 'applicant_for_job'}on{/if}"><a href="/user/applicant_for_job">{$lng_applicant_for_job}</a></li>
		{if false}<li class="{if $GETS[1] == 'subscriptions'}on{/if}"><a href="/user/subscriptions">{$lng_subscriptions}</a></li>{/if}
		<li class="{if $GETS[1] == 'settings'}on{/if}"><a href="/user/settings">{$lng_settings}</a></li>
	</ul>
	<div class="clr"></div>
</div>