{if $allasok}
  {while $allasok->walk()}
  <article class="allas">
    <div class="wrapper">
      <div class="inside">
        <div class="body">
          <div class="top">
            <div class="type">

            </div>
            <div class="kat">

            </div>
          </div>
          <div class="desc">
            {$allasok->shortDesc()}
          </div>
        </div>
        <div class="footer">
          <div class="footer-holder">
            <div class="published" data-toggle="tooltip" data-placement="bottom" title="{lang text='Álláshirdetés közzétételének ideje'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/white/calendar.svg" alt="{lang text='Álláshirdetés közzétételének ideje'}">
              </div>
              {$allasok->getPublishDate()}
            </div>
            <div class="where" data-toggle="tooltip" data-placement="bottom" title="{lang text='Munkavégzés helye'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/white/marker.svg" alt="{lang text='Munkavégzés helye'}">
              </div>
              Budapest
            </div>
            <div class="button">
              <a href="{$allasok->getURL()}">{lang text="Érdekel"}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  {/while}
{/if}
