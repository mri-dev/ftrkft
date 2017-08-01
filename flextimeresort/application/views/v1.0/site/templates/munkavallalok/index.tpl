{if $me && $me->logged() && $me->isMunkaado()}
  <div class="munkavallalok-view">
    <div class="tophead">
      <div class="page-width">
        <div class="wrapper">
          <div class="pages-info">
            <div class="">
              <h1>{lang text="Munkavállalók keresése"}</h1>
            </div>
            <span class="result-num">{$lista.info.total_num} {lang text="találat"}</span>
            <span class="current">{$lista.info.pages.current}. {lang text="oldal"}</span> / {$lista.info.pages.max} {lang text="oldal"}
          </div>
        </div>
      </div>
    </div>
    <div class="page-width">
      <div class="user-list">
        {if $lista.info.total_num == 0}
          <div class="no-user">
            <i class="fa fa-address-book "></i>
            <h2>{lang text="Nincs találat"}</h2>
            <small>{lang text="Nem találtunk a keresési feltételek alapján munkavállalót"}.</small>
            <div><a class="filterremover" href="{$settings.munkavallalo_search_slug}">{lang text="szűrőfeltételek törlése"} <i class="fa fa-refresh"></i></a></div>
          </div>
        {else}
          <div class="wrapper">
            <div class="lista">
              <div class="items">
                {foreach from=$lista.data item=u}
                  <div class="user">
                    {assign var="pp" value=$u->profilPercent()}
                    <div data-toggle="tooltip" title="{lang text='Profil kitöltöttségi állapot'}" data-placement="top" class="profil-percent st-{if $pp > 0 && $pp< 30}red{elseif $pp>=30 && $pp<50}orange{elseif $pp >=50 && $pp < 80}lightgreen{elseif $pp >= 80}green{/if}">
                      {$pp}%
                    </div>
                    <div class="wrapper">
                      <div class="profilimg">
                        <img src="{$u->getProfilImg()}" alt="{$u->getName()}">
                      </div>
                      <div class="dataset">
                        <div class="name">
                          <a href="{$u->getCVUrl()}">{$u->getName()} </a>
                        </div>
                        <div class="szakma">
                          {$u->getSzakmaText()}
                        </div>
                        {assign var="city" value=$u->cv()->City()}
                        {if !empty($city)}
                          <div class="subline">
                            <span class="city">{$city}</span>
                          </div>
                        {/if}
                      </div>
                    </div>
                    <div class="extras">
                      {assign var=iskolai_vegzettseg value=$u->cv()->getTermValues('iskolai_vegzettsegi_szintek', $u->getValue('iskolai_vegzettsegi_szintek'))}
                      {if !empty($iskolai_vegzettseg)}
                      <div class="group">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="title">
                              {lang text="Legmagasabb végzettség"}
                            </div>
                          </div>
                          <div class="col-md-8">
                            <div class="data">
                      				{$iskolai_vegzettseg.neve}
                            </div>
                          </div>
                        </div>
                      </div>
                      {/if}
                      {assign var=munkatapasztalat value=$u->cv()->getTermValues('munkatapasztalat', $u->getValue('munkatapasztalat'))}
                      {if !empty($munkatapasztalat)}
                      <div class="group">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="title">
                              {lang text="Munkatapasztalat"}
                            </div>
                          </div>
                          <div class="col-md-8">
                            <div class="data">
                        			{$munkatapasztalat.neve}
                            </div>
                          </div>
                        </div>
                      </div>
                      {/if}
                      {assign var=elvaras_munkateruletek value=$u->cv()->getTermValues('munkakorok', $u->getValue('elvaras_munkateruletek'))}
                      {if !empty($elvaras_munkateruletek)}
                        <div class="group g-listed">
                          <div class="title">
                            {lang text="Munkaterületek"}
                          </div>
                          <div class="data">
                            {foreach from=$elvaras_munkateruletek item=munkateruletek}
                              <div class="simple-list-item">{$munkateruletek.neve}</div>
                            {/foreach}
                          </div>
                        </div>
                      {/if}

                      {assign var=elvaras_munkakorok value=$u->cv()->getTermValues('munkakorok', $u->getValue('elvaras_munkakorok'))}
                      {if !empty($elvaras_munkakorok)}
                        <div class="group g-listed">
                          <div class="title">
                            {lang text="Munkakörök"}
                          </div>
                          <div class="data">
                            {foreach from=$elvaras_munkakorok item=imk}
                              <div class="simple-list-item">{$imk.parent.neve} / <strong>{$imk.neve}</strong></div>
                            {/foreach}
                          </div>
                        </div>
                      {/if}
                    </div>
                  </div>
                {/foreach}
              </div>
              {$pagination}
            </div>
          </div>
        {/if}
      </div>
    </div>
  </div>
{else}
  <div class="munkavallalok-view landing-page">
    <div class="page-width">
      <div class="header">
        <h1>{lang text="MUNKAVALLALO_LANDINGPAGE_MAIN_TITLE"}</h1>
        <div class="subtitle">
          {lang text="MUNKAVALLALO_LANDINGPAGE_SUB_TITLE"}
        </div>
      </div>
      <div class="">
        <div class="row">
          <div class="col-md-8">
            <div class="about-abs">
              {lang text="MUNKAVALLALO_LANDINGPAGE_BODY_ABSTRACT_ABOUT"}
            </div>
            <div class="why-we">
              <h2>{lang text="MUNKAVALLALO_LANDINGPAGE_WHY_TITLE"}</h2>
              <div class="content">
                {lang text="MUNKAVALLALO_LANDINGPAGE_WHY_BODY"}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="reg-instruction">
              <h2>{lang text="NINCS_MEG_FIOKJAASK"}</h2>
              <div class="text">
                {lang text="NINCS_MEG_FIOKJAASK_DESC"}
              </div>
              <a href="/regisztracio/munkaltato" class="btn btn-danger">{lang text="REGISZTRACIO_2PERC_ALATT"}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{/if}
