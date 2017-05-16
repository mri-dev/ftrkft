<div class="login-container">
  <form class="" action="" method="post">
    <div class="form-holder line">
      <input type="text" name="email" class="form-control" placeholder="{lang text='EMAIL_ADDRESS'}" value="">
    </div>
    <div class="form-holder line">
      <input type="password" name="password" class="form-control" value="">
    </div>
    <div class="actions">
      <div class="whattodo line">
        <div class="row">
          <div class="col-md-6">
            <input type="checkbox" class="ccb" id="login_remember" name="rememberme" value="1"> <label for="login_remember">{lang text="JEGYEZZE_MEG"}</label>
          </div>
          <div class="col-md-6">
            <a href="/elfelejtett-jelszo">{lang text="ELFELEJTETT_JELSZO"}</a>
          </div>
        </div>
      </div>
      <div class="sub line">
        <button type="submit" class="btn btn-success" name="ugyfelkapuLogin">{lang text="BELEPES"}</button>
      </div>
      <a href="/regisztracio" class="btn btn-info">{lang text="REGISZTRACIO"}</a>
    </div>
  </form>
</div>
