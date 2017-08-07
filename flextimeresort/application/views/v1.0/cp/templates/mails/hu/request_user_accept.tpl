{include file="mails/header.tpl"}
<h1>Tisztelt Érdeklődő!</h1>
Örömmel értesítjük, hogy hozzáférést engedélyeztek egy Ön által jelentkezett állásajánlatra.
<br><br>
<h4>Állásajánlat:</h4>
<div style="padding: 10px; margin: 5px 0 10px 0; background-color: #e8e8e8;">
  <div class="">
    <a href="{$settings.page_url}{$allas->getURL()}">{$allas->shortDesc()}</a>
  </div>
  <div class="">
    {$allas->get('tipus_name')} / {$allas->get('cat_name')} @ {$allas->getCity()}
  </div>
</div>
<br>
---
<br>
<em>Jelentkezés ideje: <strong>{$request_at}</strong></em><br>
<em>Jelentkezési azonosító: <strong>{$hashkey}</strong></em><br>
<em>Hozzáférés megadva: <strong>{$accepted_at}</strong></em><br>
{include file="mails/footer.tpl"}
