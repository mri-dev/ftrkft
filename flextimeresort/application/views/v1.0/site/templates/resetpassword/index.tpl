<div class="user-section user-resetpassword">
	<section class="resetpassword">
			<h1>{lang text="RESETPASS_EFELEJTETTE_JELSZAVATASK"}</h1>
			<div class="form">
        <div class="row col-vertical-middle">
          <div class="col-md-12">
						<form method="post" action="/forms/resetpassword">
							<input type="hidden" name="return" value="/resetpassword">
							<input type="hidden" name="form" value="1">
              <h2>{lang text="RESETPASS_UJ_JELSZO_GENERALAS"}</h2>
	            {if $form}
	  						{$form->getMsg(1)}
	  					{/if}
  						<div class="row col-vertical-middle">
  							<div class="col-md-12 {if $form && $form->hasError(1, 'email')}input-error{/if}">
  								<label for="email">{lang text="EMAIL"} *</label>
  								<input type="email" name="data[email]" class="form-control" id="email" placeholder="">
  							</div>
                <div class="divider"></div>
  							<div class="col-md-12 submit">
  								<button type="submit" class="btn btn-danger">{lang text="RESETPASS_UJ_JELSZO_KULDESE"} <i class="fa fa-refresh"></i></button>
  							</div>
  						</div>
  					</form>
          </div>
        </div>
			</div>
	</section>
</div>
