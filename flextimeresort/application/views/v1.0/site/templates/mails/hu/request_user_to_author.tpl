{include file="mails/"|cat:$settings.language|cat:"/header.tpl"}
<h1 style="margin: 0 0 10px 0;">Tisztelt {$author->getName()}!</h1>
Értesítjük, hogy <strong>{$user->getName()}</strong> munkavállaló jelentkezett az Ön egyik álláshirdetésére:<br>
<div style="padding: 10px; margin: 5px 0 10px 0; background-color: #e8e8e8;">
  <div class="">
    <a href="{$settings.page_url}{$allas->getURL()}">{$allas->shortDesc()}</a>
  </div>
  <div class="">
    {$allas->get('tipus_name')} / {$allas->get('cat_name')} @ {$allas->getCity()}
  </div>
</div>
--
<br>
<strong>A kérelem feldolgozást követően felvesszük a kapcsolatot a munkavállalóval és Önnel.</strong>
<br><br>
A hozzáférés engedélyezés után a munkavállaló hozzáférést kap a fent említett állásajánlat teljes tartalmához és a megadott kapcsolatfelvételi adatokhoz. Önnek is elérhetővé válik a munkavállaló személyes kapcsolatfelvételi adatai, így közvetlen módon felvehetik egymással a kapcsolatot.<br>
<br>
<a href="{$settings.page_url}{$user->getCVUrl()}"><strong>{$user->getName()} elektronikus önéletrajza >></strong></a>
<br>
<br>
<small>Minden kérelmet megtekinthet az ügyfélkapun belépve a <a href="{$settings.page_url}/ugyfelkapu/hirdetesek">hirdetések</a> pont alatt.</small>

{include file="mails/"|cat:$settings.language|cat:"/footer.tpl"}
