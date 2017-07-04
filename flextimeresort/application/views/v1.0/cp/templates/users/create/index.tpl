<h1><a class="backurl" href="{$root}users"><i class="fa fa-long-arrow-left"></i></a> Új felhasználó hozzáadása</h1>

<div class="box">
  <form action="/forms/admins" method="post">
    <input type="hidden" name="form" value="1">
    <input type="hidden" name="for" value="user_create">
    <input type="hidden" name="return" value="{$root}users/create">
    <input type="hidden" name="session_path" value="{$root}users">
    <a name="settings"></a>
    <h3><i class="fa fa-gear"></i> Fiók adatok</h3>
    {if $form}
      {$form->getMsg(1)}
      {assign var="formposts" value=$form->getPost()}
    {/if}
    <div class="">
      <div class="row">
        <div class="col-md-6 {if $form && $form->hasError(1, 'name')}input-error{/if}">
          <label for="name">Név / Cégnév *</label>
          <input type="text" id="name" name="data[name]" value="{if $form && $formposts}{$formposts.data.name}{/if}" class="form-control">
        </div>
        <div class="col-md-6 {if $form && $form->hasError(1, 'email')}input-error{/if}">
          <label for="email">E-mail cím *</label>
          <input type="text" id="email" name="data[email]" value="{if $form && $formposts}{$formposts.data.email}{/if}" class="form-control">
        </div>
        <div class="divider"></div>
        <div class="col-md-6 {if $form && $form->hasError(1, 'password')}input-error{/if}">
          <label for="password">Jelszó *</label>
          <input type="password" id="password" name="data[password]" value="" class="form-control">
        </div>
        <div class="col-md-6 {if $form && $form->hasError(1, 'password2')}input-error{/if}">
          <label for="password2">Jelszó újra *</label>
          <input type="password" id="password2" name="data[password2]" value="" class="form-control">
        </div>
        <div class="divider"></div>
        <div class="col-md-6 {if $form && $form->hasError(1, 'user_group')}input-error{/if}">
          <label for="user_group">Felhasználói csoport *</label>
          <select class="form-control" id="user_group" name="data[user_group]">
            <option value="" selected="selected">-- válasszon --</option>
            {foreach from=$usergroups item=ug key=id}
              <option value="{$id}" {if $form && $formposts.data.user_group != '' && $formposts.data.user_group == $id}selected="selected"{/if}>{lang text=$ug}</option>
            {/foreach}
          </select>
        </div>
        <div class="col-md-6 {if $form && $form->hasError(1, 'engedelyezve')}input-error{/if}">
          <label for="engedelyezve">Státusz *</label>
          <select class="form-control" id="engedelyezve" name="data[engedelyezve]">
            <option value="" selected="selected">-- válasszon --</option>
            <option value="0">Tiltva</option>
            <option value="1">Engedélyezve</option>
          </select>
        </div>
      </div>
      <br>
      <h3>Egyéb kiegészítő adatok</h3>
      <div class="subtitle">
        <i class="fa fa-info-circle"></i> A lehetséges paraméterek a felhasználói csoport kiválasztása esetén kerülnek megjelenítésre.
      </div>
      <div class="row">
        {foreach from=$userdetails item=ud key=key}
        <div class="col-md-6 {if $form && $form->hasError(1, 'details_'|cat:$key)}input-error{/if} group-details-prehide" data-usergroup="{$ud.group.id}">
          <label for="details_{$key}">{lang text=$ud.text}{if $ud.required == '1'}*{/if}</label>
          <input type="text" id="details_{$key}" name="data[details][{$key}]" value="{if $form && $formposts}{$formposts.data.details[$key]}{/if}" class="form-control">
        </div>
        {/foreach}
      </div>
      <div class="row">
        <div class="divider"></div>
        <div class="col-md-6 left">
          {if $formposts}
          <a href="{$root}users/create" class="btn btn-danger">vissza</a>
          {/if}
        </div>
        <div class="col-md-6 right">
          <button type="submit" class="btn btn-success">Létrehozás <i class="fa fa-plus-circle"></i></button>
        </div>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(function(){
    bindDetailsShower({$formposts.data.user_group});
    $('#user_group').change(function(){
      var v = $(this).val();
      bindDetailsShower(v);
    });

    function bindDetailsShower(v) {
      if(typeof v === 'undefined') return false;
      $('.group-details-prehide.show').removeClass('show');
      $('.group-details-prehide[data-usergroup]').each(function(i,e){
        var ug = $(e).data('usergroup');
        if(ug == 'default' || ug == v) {
          $(e).addClass('show');
        }
      });
    }
  })
</script>
