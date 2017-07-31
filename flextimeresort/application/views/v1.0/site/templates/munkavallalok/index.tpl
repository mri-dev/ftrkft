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
          <div class="order">
            <label for="sorter"><i class="fa fa-sort-amount-desc"></i> {lang text="Rendezés"}</label>
            <div class="order-select">
              <select class="form-control" id="sorter" name="order">
                <option value="">{lang text="Legújabb állások"}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="page-width">
      <div class="user-list">
        {if $lista.info.total_num == 0}
          <div class="no-user">
            <i class="fa fa-info-circle"></i>
            <h2>{lang text="Nincs találat"}</h2>
            <small>{lang text="Nem találtunk a keresési feltételek alapján munkavállalót"}.</small>
          </div>
        {else}
          <div class="wrapper">
            <div class="lista">
              <div class="items">
                {foreach from=$lista.data item=u}
                  <div class="user">
                    <div class="wrapper">
                      <div class="profilimg">
                        <img src="{$u->getProfilImg()}" alt="{$u->getName()}">
                      </div>
                      <div class="dataset">
                        <div class="name">
                          <a href="{$u->getCVUrl()}">{$u->getName()}</a>
                        </div>
                        <div class="szakma">
                          {$u->getSzakmaText()}
                        </div>
                      </div>
                    </div>
                  </div>
                {/foreach}
              </div>
              {$pagination}
            </div>
            <div class="filters">
              filters
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
