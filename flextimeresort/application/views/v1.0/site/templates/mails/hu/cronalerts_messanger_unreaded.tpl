{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h2>Tisztelt {$user.name}!</h2>
Értesítjük, hogy Önnek <strong>{$unreaded_num|intval} db olvasatlan</strong> üzenete van.
<div class="sessions">
  {foreach from=$items item=allas key=session}
  <div class="session">
    <div class="wrapper">
      <div class="subject">
        <span class="unreaded">({count($allas.items)} db olvasatlan)</span> <a href="/ugyfelkapu/uzenetek/msg/{$session}">Téma: <strong>{$allas.subject}</strong></a>
      </div>
    </div>
    {if !empty($allas.allas)}
    <div class="allas">
      <div class="title">
        Kapcsolódó állás:
      </div>
      <div class="desc">
        <a href="{$allas.allas.url}">{$allas.allas.desc}</a> &mdash; {$allas.allas.author.name}
      </div>
      <div class="type">
        {$allas.allas.tipus_name} / {$allas.allas.cat_name}
      </div>
    </div>
    {/if}
  </div>
  {/foreach}
</div>
<br><br>
Jelentkezzen be fiókjába, és tekintse meg olvasatlan üzeneteit.

<style media="screen">
  .sessions{
    margin-top: 40px;
  }
  .sessions .session{
    margin: 10px 0;
    border-radius: 10px;
    overflow: hidden;
  }
  .sessions .session .wrapper{
    padding: 15px;
    background: #f1f1f1;
  }

  .sessions .session .wrapper .subject a{
    color: #4db683;
    text-decoration: none;
  }

  .sessions .session .wrapper .subject .unreaded{
    color: red;
  }

  .sessions .session .allas{
    color: #cacaca;
    padding: 8px;
    background: #444444;
    font-size: 12px;
    line-height: 1.4;
  }

  .sessions .session .allas .title{
    color: #acacac;
    font-size: 10px;
  }

  .sessions .session .allas a{
    color: #eaeaea;
    text-decoration: none;
    font-style: italic;
    font-size: 13px;
  }
</style>

{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
