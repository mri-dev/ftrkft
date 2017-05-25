<div class="user-section user-resetpassword">
	<section class="resetpassword">
			<div class="form">
        <div class="row col-vertical-middle">
          <div class="col-md-12">
            {if $form}
  						{$form->getMsg(1)}
  					{/if}
						<form method="post" action="/forms/resetpassword">
							<input type="hidden" name="return" value="/resetpassword">
							<input type="hidden" name="form" value="1">
              <h1>{lang text="BEJELENTKEZES_FIOKJABA"}</h1>
  						<div class="row col-vertical-middle">
  							<div class="col-md-12 {if $form && $form->hasError(1, 'email')}input-error{/if}">
  								<label for="email">{lang text="EMAIL"} *</label>
  								<input type="email" name="data[email]" class="form-control" id="email" placeholder="">
  							</div>
                <div class="divider"></div>
  							<div class="col-md-6 submit">
  								<button type="submit" class="btn btn-success">{lang text="BELEPES"} <i class="fa fa-sign-in"></i></button>
  							</div>
  						</div>
  					</form>
          </div>
        </div>
			</div>
	</section>
</div>
