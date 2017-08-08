<div class="user-section user-login">
	<section class="info">
		<div class="page-width">
			<h1>{lang text="USER_LOGIN_UDVOZLO_TITLE"}</h1>
		</div>
	</section>
	<section class="login">
		<div class="page-width">
			<div class="wbox dp">
				<div class="form">
          <div class="row col-vertical-middle">
            <div class="col-md-6">
						  {assign var="remembermehash" value=\Hash::loadRememberMeHash()}
              <form class="" action="/forms/auth" method="post">
    						<input type="hidden" name="return" value="{if isset($smarty.get.re)}{$smarty.get.re}{else}{$smarty.server.REQUEST_URI}{/if}">
                <input type="hidden" name="form" value="1">
                <input type="hidden" name="session_path" value="/user/belepes">
                <h1>{lang text="BEJELENTKEZES_FIOKJABA"}</h1>
	              {if $form}
	    						{$form->getMsg(1)}
	    					{/if}
    						<div class="row col-vertical-middle">
    							<div class="col-md-12 {if $form && $form->hasError(1, 'email')}input-error{/if}">
    								<label for="email">{lang text="EMAIL"} *</label>
    								<input type="text" class="form-control" name="email" id="email" value="{if $remembermehash}{$remembermehash.email}{/if}">
    							</div>
                  <div class="divider"></div>
    							<div class="col-md-12">
    								<label for="password">{lang text="JELSZO"} *</label>
    								<input type="password" class="form-control" name="password" id="password" value="{if $remembermehash}{$remembermehash.password_hash}{/if}">
    							</div>
                  <div class="divider"></div>
                  <div class="col-md-6 actions">
                    <a href="/elfelejtett-jelszo"><span class="fa-stack">
                      <i class="fa fa-square fa-stack-2x"></i>
                      <i class="fa fa-question fa-stack-1x"></i>
                    </span> {lang text="ELFELEJTETT_JELSZO"}</a>
    							</div>
									<div class="col-md-3 actions">
                    <input type="checkbox" class="ccb" id="login_remember" {if $remembermehash}checked="checked"{/if} name="rememberme" value="1"><label for="login_remember">{lang text="JEGYEZZE_MEG"}</label>
    							</div>
    							<div class="col-md-3 submit">
    								<button type="submit" class="btn btn-success">{lang text="BELEPES"} <i class="fa fa-sign-in"></i></button>
    							</div>
    						</div>
    					</form>
            </div>
            <div class="col-md-6">
              <div class="reg-instruction">
                <h2>{lang text="NINCS_MEG_FIOKJAASK"}</h2>
                <div class="text">
                  {lang text="NINCS_MEG_FIOKJAASK_DESC"}
                </div>
                <a href="/regisztracio" class="btn btn-danger">{lang text="REGISZTRACIO_2PERC_ALATT"}</a>
              </div>
            </div>
          </div>
				</div>
			</div>
		</div>
	</section>
</div>
