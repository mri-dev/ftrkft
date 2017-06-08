<div class="user-section user-register">
	<section class="info">
		<div class="page-width">
			<h1>{lang text="USER_REGISTER_UDVOZLO_TITLE"}</h1>
			<h2>{lang text="USER_REGISTER_UDVOZLO_SUBTITLE"}</h2>
			<div class="ask-as">
				{lang text="USER_REGISTER_VALASSZON_MIKENT_REGISZTRAL"}
			</div>
		</div>
	</section>
	<section class="register">
		<div class="page-width">
			<div class="wbox dp">
				<div class="tabs">
					<ul>
						<li class="{if $as == 'munkavallalo'}active{/if}"><a href="/regisztracio/">{lang text="MUNKAVALLALOKENT"}</a></li>
						<li class="{if $as == 'munkaltato'}active{/if}"><a href="/regisztracio/munkaltato">{lang text="MUNKALTATOKENT"}</a></li>
						<li class="login"><a href="/belepes">{lang text="BELEPES"} <i class="fa fa-sign-in"></i></a></li>
					</ul>
				</div>
				<div class="form">
					<h2>{lang text="CSATLAKOZAS_MINT"} <strong>{if $as == 'munkavallalo'}{lang text="MUNKAVALLALO"}{elseif $as == 'munkaltato'}{lang text="MUNKALTATO"}{/if}</strong>:</h2>
					{if $form}
						{$form->getMsg(1)}
			      {assign var="formposts" value=$form->getPost()}
					{/if}
					<form class="" action="/forms/register" method="post">
						<input type="hidden" name="return" value="{$smarty.server.REQUEST_URI}">
            <input type="hidden" name="form" value="1">
            <input type="hidden" name="session_path" value="/user/regisztracio">
            <input type="hidden" name="data[user_group]" value="{$usergroup}">
						<div class="row col-vertical-middle">
							<div class="col-md-12 {if $form && $form->hasError(1, 'name')}input-error{/if}">
								<label for="name">{lang text="NEV_CEGNEV"} *</label>
								<input type="text" class="form-control" name="data[name]" id="name" value="{if $form}{$formposts.data.name}{/if}">
							</div>
							<div class="divider"></div>
							<div class="col-md-6 {if $form && $form->hasError(1, 'email')}input-error{/if}">
								<label for="email">{lang text="EMAIL"} *</label>
								<input type="text" class="form-control" name="data[email]" id="email" value="{if $form}{$formposts.data.email}{/if}">
							</div>
							<div class="col-md-6 {if $form && $form->hasError(1, 'telefon')}input-error{/if}">
								<label for="telefon">{lang text="TELEFON"} *</label>
								<input type="text" class="form-control" name="data[details][telefon]" id="telefon" value="{if $form}{$formposts.data.details.telefon}{/if}">
							</div>
							<div class="divider"></div>
							<div class="col-md-6 {if $form && $form->hasError(1, 'password')}input-error{/if}">
								<label for="password">{lang text="JELSZO"} *</label>
								<input type="password" class="form-control" name="data[password]" id="password" value="">
							</div>
							<div class="col-md-6 {if $form && $form->hasError(1, 'password2')}input-error{/if}">
								<label for="password2">{lang text="JELSZO_UJRA"} *</label>
								<input type="password" class="form-control" name="data[password2]" id="password2" value="">
							</div>
							<div class="divider"></div>
							<div class="col-md-8 aszf">
								<input type="checkbox" class="ccb" name="data[aszf]" id="aszf" value="1"> <label for="aszf">{lang text="ASZF_ELFOGADAS"}</label>
								{if $form && $form->hasError(1, 'aszf')}<div class="error-msg"><i class="fa fa-exclamation-circle"></i> {lang text="FORM_USER_REG_ASZF_CHECK_HIANYZIK"}</div>{/if}
							</div>
							<div class="col-md-4 submit">
								<button type="submit" class="btn btn-success">{lang text="REGISZTRACIO"} <i class="fa fa-arrow-circle-right"></i></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
