{if $allasok}
  {while $allasok->walk()}
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
                Teljes munkaidős munka
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="kat" data-toggle="tooltip" data-placement="top" title="{lang text='Álláshirdetés kategória'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/dark/user.svg" alt="{lang text='Álláshirdetés kategória'}">
              </div>
              <div class="text">
                25 év alattiaknak
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="desc">
            <div class="ico">
              <img src="{$smarty.const.IMG}icons/dark/documents.svg" alt="">
            </div>
            <div class="text">
              {$allasok->shortDesc()}
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
              {$allasok->getPublishDate()}
            </div>
            <div class="where" data-toggle="tooltip" data-placement="bottom" title="{lang text='Munkavégzés helye'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/white/marker.svg" alt="{lang text='Munkavégzés helye'}">
              </div>
              {$allasok->getCity()}
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
