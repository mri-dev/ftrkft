{if $allasok}
  {if $allasok->Count() == 0}
    <div class="no-data">
      <i class="fa fa-file-text-o"></i>
      <h2>{lang text="Nincs jelentkezés"}</h2>
      {lang text="Ön még nem jelentkezett egy állásajánlatra se."}
    </div>
  {/if}
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
                {$allasok->get('tipus_name')}
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="kat" data-toggle="tooltip" data-placement="top" title="{lang text='Álláshirdetés kategória'}.">
              <div class="ico">
                <img src="{$smarty.const.IMG}icons/dark/user.svg" alt="{lang text='Álláshirdetés kategória'}">
              </div>
              <div class="text">
                {$allasok->get('cat_name')}
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
        {assign var="request" value=$allasok->getRequest($allasok->getRequestHashkey($me->getID(), $allasok->getID()))}
        <div class="request-body {if $request.accepted == 1}accepted{else}unacceepted{/if}">
          <div class="status">
            {if $request.accepted == 1}
              <i class="fa fa-check-circle"></i> {lang text="Hozzáférés engedélyezve"}
            {else}
              <i class="fa fa-times-circle"></i> {lang text="Hozzáférés feldolgozás alatt"}
            {/if}
          </div>
          <div class="time">
            <i class="fa fa-time-o"></i> {lang text="Jelentkezés ideje"}: <strong>{$request.request_at}</strong>
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
              <span class="city">{$allasok->getCity()}</span>
            </div>
            <div class="button">
              <a href="{$allasok->getURL()}">{lang text="Adatlap"}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  {/while}
{/if}
