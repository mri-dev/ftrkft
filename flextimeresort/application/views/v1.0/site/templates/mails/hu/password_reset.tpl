{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h1>Tisztelt {$user.name},</h1>
Ön új jelszót generált weboldalunkon. <strong>Az új ideiglenes jelszava elkészült, melyet az első belépés után javasolt cserélni!</strong>
<br>
<h3>Új generált jelszava:</h3>
<strong style="background-color:#e1e1e1; padding: 5px; font-size: 18px; color:black;">{$password}</strong>
<br>
<br>
A bejelentkezéshez kattintson ide: <a href="{$settings.page_url}/belepes">{$settings.page_url}/belepes</a><br>
Új jelszavát bejelentkezés után a következő linken tudja módosítani:<br>
<a href="{$settings.page_url}/ugyfelkapu/beallitasok/#jelszo">{$settings.page_url}/ugyfelkapu/beallitasok/#jelszo</a>
{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
