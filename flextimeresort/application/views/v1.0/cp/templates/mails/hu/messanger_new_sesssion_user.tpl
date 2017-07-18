{include file="mails/header.tpl"}
<h1>Tisztelt {$user->getName()}!</h1>
Önnek 1 újonnan nyitott üzenetváltása van.
<br><br>
<div>Téma: <strong>{$subject}</strong></div>
<div style="margin: 10px 0;"><em>{$msg|nl2br}</em></div>
<div class="padding: 10px 0;">
  <a href="{$settings.page_url}{$msgurl}" style="display: block; float: left; padding: 10px 15px; background-color: #00961d; color: white; text-transform: uppercase; border-radius: 5px; font-size: 14px; text-decoration: none;">Válaszolok az üzenetre</a>
  <div style="clear:both;"></div>
</div>
<div>
  <small>(Fiókjába bejelentkezve ügyfélakupunkon keresztül üzenhet nekünk)</small>
</div>
<br>
{if $allas && !empty($allas->getID())}
<h4 style="color: black; margin: 5px 0;">Kapcsolódó állásajánlat:</h4>
<div style="padding: 10px; margin: 5px 0 10px 0; background-color: #e8e8e8;">
  <div class="">
    <a href="{$allas->getURL()}">{$allas->shortDesc()}</a>
  </div>
  <div class="">
    {$allas->get('tipus_name')} / {$allas->get('cat_name')} @ {$allas->getCity()}
  </div>
</div>
{/if}
{include file="mails/footer.tpl"}
