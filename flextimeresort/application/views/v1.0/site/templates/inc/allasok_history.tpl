{if $history}
  <div class="header color-red">
    <div class="inside">
      {lang text="Megtekintett ajánlatok"}
    </div>    
  </div>
  {while $history->walk()}
  <article class="allas">
    <div class="wrapper">
      <div class="inside">
        <div class="body">
          <div class="top">
            <div class="type" data-toggle="tooltip" data-placement="top" title="{lang text='Álláshirdetés típusa'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/dark/user_u_box.svg" alt="{lang text='Álláshirdetés típusa'}">
              </div>
              <div class="text">
                {$history->get('tipus_name')}
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="desc">
            <div class="ico">
              <img src="{$smarty.const.IMG}icons/dark/documents.svg" alt="">
            </div>
            <div class="text">
              {$history->shortDesc()}
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="footer">
          <div class="footer-holder">
            <div class="published" data-toggle="tooltip" data-placement="bottom" title="{lang text='Álláshirdetés közzétételének ideje'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/white/calendar.svg" alt="{lang text='Álláshirdetés közzétételének ideje'}">
              </div>
              {$history->getPublishDate()}
            </div>
            <div class="button">
              <a href="{$history->getURL()}">{lang text="Érdekel"}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  {/while}
{/if}
