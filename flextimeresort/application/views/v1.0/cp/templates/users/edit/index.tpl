<h1><a class="backurl" href="{$root}users"><i class="fa fa-long-arrow-left"></i></a><strong>{$user->getName()}</strong> felhasználó szerkesztése</h1>

  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <form action="/forms/admins" method="post">
          <input type="hidden" name="form" value="1">
          <input type="hidden" name="id" value="{$user->getId()}">
          <input type="hidden" name="for" value="user_edit">
          <input type="hidden" name="return" value="{$root}users/edit/{$user->getId()}">
          <input type="hidden" name="session_path" value="{$root}users">
          <h3><i class="fa fa-gear"></i> Fiók adatok</h3>
          {if $form}
            {$form->getMsg(1)}
            {assign var="formposts" value=$form->getPost()}
          {/if}
          <div class="">
            <div class="row">
              <div class="col-md-6 {if $form && $form->hasError(1, 'name')}input-error{/if}">
                <label for="name">Név / Cégnév*</label>
                <input type="text" id="name" name="data[name]" value="{if $form && $formposts}{$formposts.data.name}{else}{$user->getName()}{/if}" class="form-control">
              </div>
              <div class="col-md-6 {if $form && $form->hasError(1, 'email')}input-error{/if}">
                <label for="email">E-mail cím*</label>
                <input type="text" id="email" name="data[email]" value="{if $form && $formposts}{$formposts.data.email}{else}{$user->getEmail()}{/if}" class="form-control">
              </div>
              <div class="divider"></div>
              <div class="col-md-6">
                <label for="user_group">Felhasználói csoport*</label>
                <select class="form-control" id="user_group" name="data[user_group]">
                  {foreach from=$usergroups item=ug key=id}
                    <option value="{$id}" {if $user->getUserGroup() == $id}selected="selected"{/if}>{lang text=$ug}</option>
                  {/foreach}
                </select>
              </div>
              <div class="col-md-6">
                <label for="engedelyezve">Státusz*</label>
                <select class="form-control" id="engedelyezve" name="data[engedelyezve]">
                  <option value="0" {if !$user->isAllowed()}selected="selected"{/if}>Tiltva</option>
                  <option value="1" {if $user->isAllowed()}selected="selected"{/if}>Engedélyezve</option>
                </select>
              </div>
            </div>
            <br>
            <h3>Egyéb kiegészítő adatok</h3>
            <div class="row">
              {foreach from=$userdetails item=ud key=key}
              <div class="col-md-6 {if $form && $form->hasError(1, $key)}input-error{/if}">
                <label for="details_{$key}">{lang text=$ud.text}{if $ud.required == '1'}*{/if}</label>
                <input type="text" id="details_{$key}" name="details[{$key}]" value="{if $form && $formposts}{$formposts.details[$key]}{else}{$user->getAccountData($key)}{/if}" class="form-control">
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
                <button type="submit" class="btn btn-success">Mentés <i class="fa fa-save"></i></button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-4">
      <div class="box">
        <h3><i class="fa fa-lock"></i> Új jelszó beállítás</h3>
        <div class="subtitle">
          A felhasználó e-mailt kap a jelszó változásról, melyben megkapja az új beállított jelszót.
        </div>
        {if $form}
          {$form->getMsg(2)}
        {/if}
        <br>
        <div class="row">
          <div class="col-md-12">
            <label for="pw">Új jelszó beállítása</label>
            <input type="text" name="pw" id="pw" value="" autocomplete="off" class="form-control">
          </div>
          <div class="divider"></div>
          <div class="col-md-12 right">
            <button type="submit" class="btn btn-danger">Jelszó csere <i class="fa fa-refresh"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>