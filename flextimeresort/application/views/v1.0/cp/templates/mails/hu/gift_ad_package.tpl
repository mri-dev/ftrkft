{include file="mails/header.tpl"}

<div style="padding: 15px;">
	<h2>Tisztelt {$user->getName()}!</h2>
	<br>
	Az Ön fiókjához jóváírtuk a(z) <strong>{$csomag}</strong> hirdetői csomagunkat, ami feljogosítja INGYEN <strong>{$hirdetes} darab</strong> hirdetés létrehozására. 
	<br>
	<br>
	A csomag felhasználhatósága nincs időhöz kötve, bármeddig felhasználhatja. 
	<br>
	<br>
	Sok sikert kívánunk a munkaerő toborzásásban!
</div>

{include file="mails/footer.tpl"}