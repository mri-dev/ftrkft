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
          <label for="name">Név / Cégnév*</label>
          <input type="text" id="name" name="data[name]" value="{if $form && $formposts}{$formposts.data.name}{/if}" class="form-control">
        </div>
        <div class="col-md-6 {if $form && $form->hasError(1, 'email')}input-error{/if}">
          <label for="email">E-mail cím*</label>
          <input type="text" id="email" name="data[email]" value="{if $form && $formposts}{$formposts.data.email}{/if}" class="form-control">
        </div>
        <div class="divider"></div>
        <div class="col-md-6">
          <label for="user_group">Felhasználói csoport*</label>
          <select class="form-control" id="user_group" name="data[user_group]">
            {foreach from=$usergroups item=ug key=id}
              <option value="{$id}">{lang text=$ug}</option>
            {/foreach}
          </select>
        </div>
        <div class="col-md-6">
          <label for="engedelyezve">Státusz*</label>
          <select class="form-control" id="engedelyezve" name="data[engedelyezve]">
            <option value="0">Tiltva</option>
            <option value="1">Engedélyezve</option>
          </select>
        </div>
      </div>
      <br>
      <h3>Egyéb kiegészítő adatok</h3>
      {$userdetails|print_r}
      <div class="row">
        {foreach from=$userdetails item=ud key=key}
        <div class="col-md-6 {if $form && $form->hasError(1, $key)}input-error{/if} group-details-prehide" data-usergroup="{$ud.group.id}">
          <label for="details_{$key}">{lang text=$ud.text}{if $ud.required == '1'}*{/if}</label>
          <input type="text" id="details_{$key}" name="details[{$key}]" value="{if $form && $formposts}{$formposts.details[$key]}{/if}" class="form-control">
        </div>
        {/foreach}
      </div>
      <div class="row">
        <div class="divider"></div>
        <div class="col-md-6 left">
          {if $formposts}
          <a href="{$root}users/edit/{$user->getId()}" class="btn btn-danger">vissza</a>
          {/if}
        </div>
        <div class="col-md-6 right">
          <button type="submit" class="btn btn-success">Létrehozás <i class="fa fa-plus-circle"></i></button>
        </div>
      </div>
    </div>
  </form>
</div>
