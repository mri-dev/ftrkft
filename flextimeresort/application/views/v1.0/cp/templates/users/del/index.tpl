<h1><a class="backurl" href="{$root}users"><i class="fa fa-long-arrow-left"></i></a><strong>{$user->getName()}</strong> felhasználó végleges törlése</h1>

<div class="box delete">
  <form action="/forms/admins" method="post">
    <input type="hidden" name="form" value="1">
    <input type="hidden" name="id" value="{$user->getId()}">
    <input type="hidden" name="for" value="user_del">
    <input type="hidden" name="return" value="{$root}users">
    <div style="font-size: 1.8em;">
      Biztos, hogy végleg törli a(z) <strong>{$user->getName()}</strong> ({$user->getEmail()}) felhasználót? A művelet nem visszavonható!
    </div>
    <br><br>
    <div class="row">
      <div class="col-md-12 right">
        <a href="{$root}users" class="btn btn-default pull-left"><i class="fa fa-long-arrow-left"></i> mégse</a>
        <button type="submit" class="btn btn-danger">Végleges törlés</button>
      </div>
    </div>
  </form>
</div>
