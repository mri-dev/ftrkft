{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h1>Tisztelt Felhasználónk!</h1>
Elkészült új jelszava, melyet weboldalunkon cserélt le.
<br>
<h3>Az Ön újonnan beállított jelszava:</h3>
<strong style="background-color:#e1e1e1; padding: 5px; font-size: 18px; color:black;">{$password}</strong>
<br><br>
Mostantól ezzel a jelszóval tud bejelentkezni fiókjába.
<br>
A bejelentkezéshez kattintson ide: <a href="{$settings.page_url}/belepes/?relogin=1">{$settings.page_url}/belepes</a>
{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
