{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h2>Tisztelt {$user.name}!</h2>
Értesítjük, hogy az álláshirdetései kapcsán igényelt munkavállalói adatbekérés során eddig <strong>{$unreaded_num|intval} db munkavállalóval</strong> tudtuk felvenni a kapcsolatot.
<br><br>
<h3>Állásajánlatok szerint csoportosított munkavállalói visszajelzések:</h3>
<div class="sessions">
  {foreach from=$items item=allas key=session}
  <div class="session">
    {if !empty($allas.allas)}
    <div class="allas">
      <div class="desc">
        <a href="{$allas.allas.url}">{$allas.allas.desc}</a> &mdash; {$allas.allas.author.name}
      </div>
      <div class="type">
        {$allas.allas.tipus_name} / {$allas.allas.cat_name} (#ID: {$session})
      </div>
      {if $allas.admin}
      <div class="admin">
        &mdash;<br>
        Ügyintéző: <strong>{$allas.admin.name}</strong> (<a href="mailto:{$allas.admin.email}">{$allas.admin.email}</a>)
      </div>
      {/if}
    </div>
    {/if}
    <div class="wrapper">
      <div class="users">
        {foreach from=$allas.data item=u}
        <div class="user">
          <div class="wrapper">
            <div class="img">
              <img src="{$u.user.profilimg}" alt="{$u.user.name}">
            </div>
            <div class="data">
              <div class="name">
                <a href="{$u.user.url}">{$u.user.name}</a>
              </div>
              <div class="szakma">
                {$u.user.szakma}
              </div>
              <div class="city">
                {$u.user.city}
              </div>
            </div>
            <div class="status">
              <div class="">
                Igénybejelentés ideje: <strong>{$u.requested_at}</strong>
              </div>
              <div class="">
                Visszajelzés: <strong>
                  {if $u.feedback == 1}
                  <span class="t-green">Pozitív - Érdekli az ajánlat.</span>
                  {/if}

                  {if $u.feedback == 0}
                  <span class="t-red">Negatív - Nem érdekli az ajánlat.</span>
                  {/if}
                </strong>
              </div>
              <div class="">
                Hozzáférés megadva*: <strong>
                  {if $u.access_granted == 1}
                  <span class="t-green">IGEN</span> ({$u.granted_date_at})
                  {/if}

                  {if $u.access_granted == 0}
                  <span class="t-red">NEM</span>
                  {/if}
                </strong>
              </div>
            </div>
          </div>
        </div>
        {/foreach}
      </div>
    </div>
  </div>
  {/foreach}
</div>
<small>* a hozzáférés korlátozott ideig biztosít hozzáférést a munkavállaló önéletrajzán szeretplő személyes adatokhoz. A korlátozás ideje {$settings.USERREQUEST_ACCESS_GRANTED_DATEDIFF} nap, mely az engedélyezéstől számítandó.</small>

<br><br><br>
<strong>Bármilyen kérdése van, kérjük, hogy állásajánlatonként a megjelölt ügyintézővel vegye fel a kapcsolatot.</strong>
<br><br>
Minden munkavállalói adatigényléseit megtekintheti ügyfélkapunkon a <a href="{$settings.page_url}/ugyfelkapu/hirdetesek">hirdetéseinél</a>.

<style media="screen">
  .sessions{
    margin-top: 10px;
  }
  small {
    font-size: 10px;
    color: #888888;
  }
  h3{
    color: #20ab37;
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
  .sessions .session > .wrapper .users{
    line-height: 1.4;
    border-bottom: 1px solid #e1e1e1;
    padding: 10px 0;
  }
  .sessions .session > .wrapper .users .user{
    line-height: 1.4;
    border-bottom: 1px solid #e1e1e1;
    padding: 10px 0;
  }
  .sessions .session > .wrapper .users .user > .wrapper{
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }
  .sessions .session > .wrapper .users .user > .wrapper .img{
    height: 80px;
    flex-basis: 80px;
    overflow: hidden;
    background: #ffffff;
    -webkit-border-radius: 80px;
    -moz-border-radius: 80px;
    border-radius: 80px;
    text-align: center;
    position: relative;
  }
  .sessions .session > .wrapper .users .user > .wrapper .img img{
    height: 110%;
    max-height: 110%;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    position: absolute;
  }
  .sessions .session > .wrapper .users .user > .wrapper .data{
    flex-basis: calc(60% - 40px);
  }
  .sessions .session > .wrapper .users .user > .wrapper .status{
    font-size: 12px;
    color: #777777;
    flex-basis: calc(40% - 40px);
  }
  .sessions .session > .wrapper .users .user > .wrapper .status strong{
    color: #222;
  }
  .sessions .session > .wrapper .users .user > .wrapper .data > div{
    padding-left: 25px;
  }
  .sessions .session > .wrapper .users .user:last-child{
    border-bottom: none;
  }
  .sessions .session > .wrapper .users .user .szakma{
    color: #888888;
    font-size: 12px;
  }
  .sessions .session > .wrapper .users .user .city{
    color: #333333;
    font-size: 12px;
  }
  .sessions .session > .wrapper .users .user a{
    color: #4db683;
    text-decoration: none;
    font-weight: bold;
  }

  .sessions .session .wrapper .subject .unreaded{
    color: red;
  }

  .sessions .session .allas{
    color: #8c8c8c;
    padding: 8px;
    background: #444444;
    font-size: 13px;
    line-height: 1.4;
  }

  .sessions .session .allas a{
    color: #eaeaea;
    text-decoration: none;
    font-style: italic;
    font-size: 14px;
  }

  .sessions .session .allas .admin{
    color: #50d66a;
    font-size: 11px;
  }
  .sessions .session .allas .admin a{
    color: #50d66a;
  }
</style>

{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
