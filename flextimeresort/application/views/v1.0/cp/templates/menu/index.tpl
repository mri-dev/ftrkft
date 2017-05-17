<h1>Menük</h1>
<div class="row">
  <div class="col-md-4">
    <div class="box">
      1
    </div>
  </div>
  <div class="col-md-8">
    <div class="box">
      <h3>Menü elemek</h3>
      <div class="menu-list">
        <div class="row row-head">
          <div class="col-md-3"><strong>Felirat</strong></div>
          <div class="col-md-2"><strong>Típus</strong></div>
          <div class="col-md-1 center"><strong>Sorrend</strong></div>
          <div class="col-md-1 center"><strong>Látható</strong></div>
        </div>
      {while $menus->walk()}
        {assign var="menu" value=$menus->the_menu()}
        {assign var="menutype" value=$menus->the_menu_type()}
        <div class="row">
          <div class="col-md-3">
            <div><strong>{$menu.nev}</strong></div>
            <span class="position">{$menu.gyujto}</span>
            {if $menutype.type == 'url'}
            <span class="url">
              URL: <a href="{$menu.url}" target="_blank">{$menu.url}</a>
            </span>
            {/if}
          </div>
          <div class="col-md-2">
            {$menutype.type}
          </div>
          <div class="col-md-1 center">{$menu.sorrend}</div>
          <div class="col-md-1 center">
            {if $menu.lathato == '1'}
            <i class="fa fa-check"></i>
            {else}
            <i class="fa fa-time"></i>
            {/if}
          </div>
        </div>
      {/while}
      </div>
    </div>
  </div>
</div>
