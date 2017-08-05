{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h2>Tisztelt {$user.name}!</h2>
Értesítjük, hogy <strong>{$unreaded_num|intval} db állásajánlatra igényeltek</strong> hozzáférést az Ön személyes adataihoz a munkaadók.
<br><br>
<h3>Az alábbi állásajánatokkal kapcsolatban hamarosan felvesszük Önnel a kapcsolatot:</h3>
<div class="sessions">
  {foreach from=$items item=allas key=session}
  <div class="session">
    {if !empty($allas.allas)}
    <div class="allas">
      <div class="desc">
        <a href="{$allas.allas.url}">{$allas.allas.desc}</a> &mdash; {$allas.allas.author.name}
      </div>
      <div class="type">
        {$allas.allas.tipus_name} / {$allas.allas.cat_name}
      </div>
      <div class="info">
        &mdash; <br>
        Adatigénylés ideje: <strong>{$allas.data[0].requested_at}</strong>
      </div>
    </div>
    {/if}
  </div>
  {/foreach}
</div>
<br>
<h3>Az Ön jelenleg elérhető kapcsolat adatai:</h3>
<table>
  <tbody>
    <tr>
      <td>Név:</td>
      <td><strong>{$user.name}</strong></td>
    </tr>
    <tr>
      <td>E-mail:</td>
      <td><strong>{$user.email}</strong></td>
    </tr>
    <tr>
      <td>Telefonszám:</td>
      <td><strong>{$user.phone}</strong></td>
    </tr>
  </tbody>
</table>
<small class="t-red">A fent jelölt kapcsolati adatok egyikén hamarosan keresni fogjuk. Amennyiben megváltozott bármelyik adat, kérjük, hogy lépjen be fiókjába és a <a href="{$settings.page_url}/ugyfelkapu/profil/">profil szerkesztés alatt aktualizálja adatait.</a></small> 

<style media="screen">
  .sessions{
    margin-top: 10px;
  }

  h3{
    color: #20ab37;
  }

  table tr td {
    padding: 5px;
  }
  small {
    font-size: 10px;
    color: #888888;
  }
  .t-green{
    color: green;
  }
  .t-red {
    color: red;
  }
  .sessions .session{
    margin: 10px 0;
    border-radius: 10px;
    overflow: hidden;
  }
  .sessions .session > .wrapper{
    padding: 0 15px;
    background: #f1f1f1;
  }
  .sessions .session .allas{
    color: #8c8c8c;
    padding: 8px;
    background: #444444;
    font-size: 13px;
    line-height: 1.4;
  }

  .sessions .session .allas .desc{
    font-size: 8px;
  }

  .sessions .session .allas a{
    color: #36bd4d;
    text-decoration: none;
    font-style: italic;
    font-size: 14px;
  }

  .sessions .session .allas .info{
    color: #b5b5b5;
    font-size: 10px;
  }
  .sessions .session .allas .info strong{
    color: #c5c5c5;
  }
</style>

{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
