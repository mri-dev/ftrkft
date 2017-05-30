{include file="mails/header.tpl"}
<h1>Tisztelt {$name}!</h1>
Önnek új jelszó lett beállítva a(z) <strong>{$email}</strong> fiókjához, melyet adminisztrátorunk állított be.
<br>
<h3>Az Ön újonnan beállított jelszava:</h3>
<strong style="background-color:#e1e1e1; padding: 5px; font-size: 18px; color:black;">{$password}</strong>
<br><br>
Mostantól ezzel a jelszóval tud bejelentkezni fiókjába.
<br>
A bejelentkezéshez kattintson ide: <a href="{$settings.page_url}/belepes/?relogin=1">{$settings.page_url}/belepes</a>
{include file="mails/footer.tpl"}
 
