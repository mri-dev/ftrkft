<div class="allas-view">
  <div class="header">
    <div class="page-width">
      <h1>{$allas->shortDesc()}</h1>
      <div class="types">
        <div class="tipus" data-toggle="tooltip" data-placement="bottom" title="{lang text='Álláshirdetés típusa'}.">
          {$allas->get('tipus_name')}
        </div>
        <div class="cat" data-toggle="tooltip" data-placement="bottom" title="{lang text='Álláshirdetés kategória'}.">
          {$allas->get('cat_name')}
        </div>
      </div>
    </div>
  </div>
  <div class="page-width">
    <div class="allas-wrapper">
      <div class="content-side">
        {if $access_granted}
        {else}
        <div class="content-text">
          {$allas->getPreContent()}
          <div class="view-design-fullcontent">
            <div class="text">
              <div class="">
                <h4>{lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_TITLE"}</h4> 
              </div>
              {lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_MSG"}
            </div>
          </div>
        </div>
        {/if}
      </div>
      <div class="info-side">

      </div>
    </div>
  </div>
  {if !$access_granted}
  <div class="accept-for-ad">
    <div class="page-width">
      3
    </div>
  </div>
  {/if}
</div>
