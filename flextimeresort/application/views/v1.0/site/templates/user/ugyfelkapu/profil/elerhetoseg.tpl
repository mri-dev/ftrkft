{if $me && $me->isMunkaado()}
<div class="group">
  <div class="title">
    <h3><i class="fa fa-user"></i> {lang text="Kapcsolattartó"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="elerhetoseg" item="kapcsolattartok"></profil-modul>
</div>
{/if}

<div class="group">
  <div class="title">
    <h3><i class="fa fa-phone"></i> {lang text="Telefonszámok"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="elerhetoseg" item="telefon"></profil-modul>
</div>

{if $me && $me->isUser()}
<div class="group">
  <div class="title">
    <h3><i class="fa fa-address-card-o "></i> {lang text="Lakcím"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="elerhetoseg" item="lakcim"></profil-modul>
</div>
{/if}

{if $me && $me->isMunkaado()}
<div class="group">
  <div class="title">
    <h3><i class="fa fa-map-pin "></i> {lang text="Székhely"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="elerhetoseg" item="szekhely"></profil-modul>
</div>
{/if}

<div class="group">
  <div class="title">
    <h3><i class="fa fa-globe"></i> {lang text="Közösségi oldalak"}</h3>
    <div class="line"></div>
    <div class="clearfix"></div>
  </div>
  <profil-modul group="elerhetoseg" item="social"></profil-modul>
</div>
