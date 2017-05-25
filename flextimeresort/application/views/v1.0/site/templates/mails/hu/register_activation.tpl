{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h1>Tisztelt {$account.name},</h1>
sikeresen regisztrálta fiókját weboldalunkon. Ahhoz, hogy regisztrációját folytassa, aktiválnia kell jelentkezését.
<p>
  <strong>Az alábbi linkre kattintva aktiválhatja regisztrációját:</strong> <br>
  <a style="font-size: 13px; color: green;" href="{$activateURL}">{$activateURL}</a>
</p>
{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
