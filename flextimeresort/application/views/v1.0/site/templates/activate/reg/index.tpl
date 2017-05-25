<div class="activator-content">
	<div class="pw">
		<div>
			<div style="padding:50px;" align="center">
				{if $err}
					<h1>{$msg}</h1>
					<a href="/belepes" class="btn btn-danger btn-md">{lang text="BEJELENTKEZES"} <i class="fa fa-sign-in"></i></a>
				{else}
					<h1>{lang text="ACTIVATE_SIKERES_AKTIVALAS"}</h1>
	        <div class="sub">{lang text="ACTIVATE_SIKERES_AKTIVALAS_TEXT"}</div>
	        <div>
	        	<a href="/belepes" class="btn btn-danger btn-md">{lang text="BEJELENTKEZES"} <i class="fa fa-sign-in"></i></a>
	        </div>
				{/if}
		    </div>
		</div>
	</div>
</div>
