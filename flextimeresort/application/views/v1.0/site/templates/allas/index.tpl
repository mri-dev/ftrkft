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
        </div>
        {/if}
      </div>
      <div class="info-side">
        <div class="e">
          <div class="title">{lang text="Megye / Város"}</div>
          <div class="val">
            {$allas->getMegye()}, {$allas->getCity()}
          </div>
        </div>
        <div class="e">
          <div class="title">{lang text="Megye"}</div>
          <div class="val">
            {$allas->getMegye()}
          </div>
        </div>
        <div class="e">
          <div class="title">{lang text="Kulcsszavak"}</div>
          <div class="val">
            {foreach from=$allas->getKeywords() item=keyword}
            <a href="#">{$keyword}</a>
            {/foreach}
          </div>
        </div>
      </div>
    </div>
  </div>
  {if !$access_granted}
  <div class="accept-for-ad">
    <div class="page-width">
      <div class="view-design-fullcontent">
        <div class="ico">
          <i class="fa fa-lock"></i>
        </div>
        <div class="text">
          <div class="">
            <h4>{lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_TITLE"}</h4>
          </div>
          {lang text="ALLAS_ADATLAP_TOVABBI_ADATOK_MSG"}
          <div class="infotext">
            {lang text="További információkat szeretnék_MSG"}
          </div>
          <div class="request-access">
            <button class="btn btn-danger" type="button">{lang text="További információkat szeretnék"} <i class="fa fa-arrow-circle-right"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  {/if}
</div>
