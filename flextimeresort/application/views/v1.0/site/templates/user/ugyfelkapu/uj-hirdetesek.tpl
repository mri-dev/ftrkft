<div class="advertise-creator" ng-app="Ads" ng-controller="Creator" ng-init="init(0, {$me->getID()}, {$smarty.get.modid|intval})">
  <a class="btn btn-sm btn-default" href="/ugyfelkapu/hirdetesek/"><i class="fa fa-angle-left"></i> {lang text="vissza a hirdetésekhez"}</a>
  <br><br>
  <div ng-show="!dataloaded" class="alert alert-warning">
    <i class="fa fa-spin fa-spinner"></i> {lang text="Szükséges modulok betöltése folyamatban."}
  </div>
  <div ng-show="(settings.edit_ad_id != 0 && !editing_data_loaded)?true:false" class="alert alert-warning">
    <i class="fa fa-spin fa-spinner"></i> {lang text="Ajánlat adatainak betöltése folyamatban."}
  </div>
  <div ng-show="( (settings.edit_ad_id == 0 && dataloaded) || (dataloaded && settings.edit_ad_id != 0 && editing_data_loaded))?true:false">
    {if $smarty.get.justcreated == '1'}
      <div class="alert alert-success align-center big-fa">
        <i class="fa fa-check-circle"></i>
        {lang text="sikeres létrehozás justcreated msg"}
      </div>
    {/if}
    <div ng-show="(settings.edit_ad_id != 0)">
      <h3>{lang text="Művelet végrehajtások"}</h3>
      <div class="row">
        <div class="col-md-4">
          <input type="checkbox" ng-model="allas.active" ng-checked="allas.active" class="ccb" id="aktiv"><label for="aktiv">{lang text="Aktív"}</label>
        </div>
        <div class="col-md-4">
          <input type="checkbox" ng-model="allas.betoltott" ng-checked="allas.betoltott" class="ccb" id="betoltott"><label for="betoltott">{lang text="Betöltött pozíció"}</label>
        </div>
      </div>
    </div>

    <h3>{lang text="Hirdetés alapadatok"}</h3>

    <div class="row">
      <div class="col-md-3">
        <label for="allas_hirdetes_tipusok">{lang text="Állásajánlat nyelve"} *</label>
        <div class="input-wrapper">
          <select class="form-control" ng-model="allas.language" required="required">
            {foreach from=$active_langs item=lang}
              <option value="{$lang.code}">{$lang.nametext}</option>
            {/foreach}
          </select>
          <div class="form-helper"></div>
        </div>
      </div>
    </div>

    <div class="infotext">
      {lang text="ALLAS_LANGUAGE_SELECT_HINT"}
    </div>

    <div class="row">
      <div class="col-md-6">
        <label for="allas_hirdetes_tipusok">{lang text="Állás típusa"} *</label>
        <div class="input-wrapper">
          {$formdesigns->singleSelector('hirdetes_tipus', 'hirdetes_tipusok')}
          <div class="form-helper"></div>
        </div>
      </div>
      <div class="col-md-6">
        <label for="allas_hirdetes_kategoria">{lang text="Állás kategória"} *</label>
        <div class="input-wrapper">
          {$formdesigns->singleSelector('hirdetes_kategoria', 'hirdetes_kategoria')}
          <div class="form-helper"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <label for="allas_megye">{lang text="Megye"} *</label>
        <div class="input-wrapper">
          {$formdesigns->singleSelector('megye_id', 'megyek')}
          <div class="form-helper"></div>
        </div>
      </div>
      <div class="col-md-6">
        <label for="allas_city">{lang text="Munkavégzés helye (város)"} *</label>
        <div class="input-wrapper">
          <input type="text" id="allas_city" required="required" placeholder="{lang text='A város neve...'}" ng-model="allas.city" class="form-control" />
          <div class="form-helper"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <label for="allas_megye">{lang text="Munkakörök"} *</label>
        <div class="input-wrapper">
          {$formdesigns->multiSelector('munkakorok', 'munkakor')}
          <div class="form-helper"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <label for="allas_short_desc">{lang text="Rövid ismertető"} *</label>
        <div class="input-wrapper">
          <textarea id="allas_short_desc" maxlength="150" required="required" ng-model="allas.short_desc" ng-change="short_desc_length = 150-allas.short_desc.length" class="form-control"></textarea>
          <div class="form-helper"></div>
        </div>
        <div class="infotext">
          <span ng-class="(short_desc_length<20 && short_desc_length != 0)?'text-color-orange':( (short_desc_length == 0)?'text-color-red':'' )">[[short_desc_length]] {lang text="karakter maradt"}</span> &mdash; {lang text="Az itt leírt szöveg jelenik meg a listázásnál, mint ismertető szöveg."}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <label for="allas_keywords">{lang text="Kulcsszavak"}</label>
        <div class="input-wrapper">
          <input type="text" id="allas_keywords" maxlength="100" ng-model="allas.keywords" ng-change="keywords_length = 100-allas.keywords.length" class="form-control" />
          <div class="form-helper"></div>
        </div>
        <div class="infotext">
          <span ng-class="(keywords_length<20 && keywords_length != 0)?'text-color-orange':( (keywords_length == 0)?'text-color-red':'' )">[[keywords_length]] {lang text="karakter maradt"}</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <label for="allas_pre_content">{lang text="Hozáférés nélküli, publikus leírás"} *</label>
        <div class="infotext">
          {lang text="Hozáférés nélküli, publikus leírás_TEXT"}
        </div>
        <div class="input-wrapper">
          <textarea ui-tinymce="tinymceOptions" id="allas_pre_content" reuired="required" ng-model="allas.pre_content"></textarea>
          <div class="form-helper"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <label for="allas_content">{lang text="Részletes leírás - hozzáféréssel rendelkezőknek"} *</label>
        <div class="infotext">
          {lang text="Részletes leírás - hozzáféréssel rendelkezőknek_TEXT"}
        </div>
        <div class="input-wrapper">
          <textarea ui-tinymce="tinymceOptions" id="allas_content" reuired="required" ng-model="allas.content"></textarea>
          <div class="form-helper"></div>
        </div>
      </div>
    </div>

    <h3>{lang text="Tematikus lista paraméterek"}</h3>
    <div class="hint">
      {lang text="Tematikus lista paraméterek_HINT"}
    </div>
    <div class="tematic-list-params">
      <div class="listgroup" ng-repeat="(index, list) in tematics">
        <div class="row">
          <div class="col-md-1">
            <div class="param-index">
              [[index+1]]
            </div>
          </div>
          <div class="col-md-4">
            <select ng-model="tematics[index].value" class="form-control">
              <option value="[[termkey]]" ng-repeat="(termkey, term) in term_list" ng-if="term.parameter_select_for_ads=='1'">[[term.neve]]</option>
            </select>
            <div class="infotext" ng-show="!tematics[index].value">
              {lang text="Válasszon egy paraméter listát."}
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" class="form-control" ng-model="tematics[index].title" placeholder="{lang text='Paraméter fejléc'}">
            <div class="infotext">
              {lang text="Pl.: Szükséges nyelvismeret"}
            </div>
          </div>
          <div class="col-md-1">
            <div class="param-remover" title="{lang text='Paraméter lista eltávolítása'}" ng-click="removeParamList(index)">
              <i class="fa fa-times"></i>
            </div>
          </div>
          <div class="col-md-10 offset-md-1">
            <div class="value-selection" ng-show="tematics[index].value">
              <div class="select-list">
                <div class="value-viewer">
                  <input type="text" class="form-control viewer" ng-click="(tematics[index].listToggled) ? tematics[index].listToggled=false:tematics[index].listToggled=true" ng-value="tematics[index].selectedNames.join(', ')" placeholder="{lang text='Kérjük, válasszon!'}" readonly="readonly" data-listindex="[[index]]">
                  <input type="hidden" id="[[termkey]]_select_id">
                  <div class="helper">
                    <i class="fa fa-angle-down"></i>
                  </div>
                </div>
                <div class="single-selector-holder" ng-show="(tematics[index].listToggled) ? true : false">
                  <div class="selector-wrapper">
                    <div ng-class="'selector-row '+(tematics[index].selectedValues.indexOf(item.id) != -1 ? 'selected' : '')" ng-click="tematicsValueset(index, item.id, item.value)" data-listindex="[[index]]" ng-repeat="item in terms[tematics[index].value]">[[item.value]]</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <button type="button" ng-click="newTematicListParameter()" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {lang text="Új tematikus lista paraméterek"}</button>
      </div>
    </div>

    <h3>{lang text="Kapcsolatfelvétel"}</h3>
    <div class="hint">
      {lang text="ALLASHIRDETES_NEW_KAPCSOLATFELVETEL_HINT"}
    </div>
    <div class="row">
      <div class="col-md-4">
        <label for="allas_author_name">{lang text="Hirdető neve"}</label>
        <div class="input-wrapper">
          <input type="text" id="allas_author_name" ng-model="allas.author_name" class="form-control" />
          <div class="form-helper"></div>
        </div>
        <div class="infotext">
          {lang text="Alapértelmezett"}: <strong>[[userdata.data.name]]</strong>
        </div>
      </div>
      <div class="col-md-4">
        <label for="allas_author_phone">{lang text="Hirdető telefonszáma"}</label>
        <div class="input-wrapper">
          <input type="text" id="allas_author_phone" ng-model="allas.author_phone" class="form-control" />
          <div class="form-helper"></div>
        </div>
        <div class="infotext">
          {lang text="Alapértelmezett"}: <strong>[[userdata.data.telefon]]</strong>
        </div>
      </div>
      <div class="col-md-4">
        <label for="allas_author_email">{lang text="Hirdető e-mail címe"}</label>
        <div class="input-wrapper">
          <input type="text" id="allas_author_email" ng-model="allas.author_email" class="form-control" />
          <div class="form-helper"></div>
        </div>
        <div class="infotext">
          {lang text="Alapértelmezett"}: <strong>[[userdata.data.email]]</strong>
        </div>
      </div>
    </div>

    <div class="buttons" ng-show="cansavenow">
      <div ng-show="(settings.edit_ad_id != 0 && !editing_data_loaded)?true:false" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Ajánlat adatainak betöltése folyamatban."}
      </div>
      <div ng-show="creator_saved && !creator_in_progress" class="alert alert-success">
        <i class="fa fa-check-circle"></i> {lang text="Sikeresen módosította az adatokat."}
      </div>
      <div ng-show="creator_created && !creator_in_progress" class="alert alert-success">
        <i class="fa fa-check-circle"></i> {lang text="Sikeresen létrehozta a hirdetést."}
      </div>
      <div ng-show="creator_in_progress" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Művelet végrehajtása folyamatban. Kis türelmét kérjük."}
      </div>
      <div ng-show="creator_error_msg" class="alert alert-error">
        <i class="fa fa-exclamation-triangle"></i> [[creator_error_msg]]
      </div>

      <button ng-show="(!creator_in_progress && !creator_created) && settings.edit_ad_id == 0" class="btn btn-danger" ng-click="create()">{lang text="Hirdetés létrehozása"} <i class="fa fa-plus-circle"></i></button>
      <button ng-show="(!creator_in_progress && !creator_created) && settings.edit_ad_id != 0" class="btn btn-success" ng-click="create()">{lang text="Hirdetés adatok mentése"} <i class="fa fa-save"></i></button>
    </div>
  </div>
</div>

<script type="text/javascript">
{literal}
$(function(){
  var input = document.getElementById('allas_city');
  var options = {
    types: ['(cities)'],
    componentRestrictions: {country: "hu"}
  };
  autocomplete = new google.maps.places.Autocomplete(input, options);

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      $('#allas_city').val(place.address_components[0].long_name);

      var scope = angular.element($("#allas_city")).scope();
      scope.$apply(function(){
        scope.allas.city = place.address_components[0].long_name;
      });
  });

  $('body').click(function(e){
    if($(e.target).hasClass('viewer') || $(e.target).hasClass('selector-row')){

    } else {
      var scope = angular.element($('.advertise-creator')).scope();
      if (scope.tematics) {
        $.each(scope.tematics, function(i,e){
          scope.$apply(function(){
            scope.tematics[i].listToggled = false;
          });
        });
      }
    }
  });
})
{/literal}
</script>
