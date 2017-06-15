<div class="advertise-creator" ng-app="Ads" ng-controller="Creator" ng-init="init(0, {$me->getID()})">
  <div ng-show="!dataloaded" class="alert alert-warning">
    <i class="fa fa-spin fa-spinner"></i> {lang text="Szükséges modulok betöltése folyamatban."}
  </div>
  <div ng-show="dataloaded">
    <h3>{lang text="Hirdetés alapadatok"}</h3>

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
        <label for="allas_short_desc">{lang text="Rövid ismertető"} *</label>
        <div class="input-wrapper">
          <textarea id="allas_short_desc" maxlength="150" required="required" ng-model="allas.short_desc" ng-change="short_desc_length = 150-allas.short_desc.length" class="form-control"></textarea>
          <div class="form-helper"></div>
        </div>
        <div class="infotext">
          [[short_desc_length]] {lang text="karakter maradt"}
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
          [[keywords_length]] {lang text="karakter maradt"}
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
          <textarea id="allas_pre_content" data-angmodel="pre_content" data-angobj="allas" reuired="required" ng-model="allas.pre_content" class="form-control editor"></textarea>
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
          <textarea id="allas_content" data-angmodel="content" data-angobj="allas" maxlength="150" reuired="required" ng-model="allas.content" class="form-control editor"></textarea>
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
            [[index+1]]
          </div>
          <div class="col-md-11">
            <select ng-model="tematics[index].value" class="form-control">
              <option value="[[termkey]]" ng-repeat="(termkey, term) in term_list">[[term.neve]]</option>
            </select>
             [[list]]
             <div class="value-selection" ng-show="tematics[index].value">
               <div class="" ng-repeat="tval in terms[tematics[index].value]">
                 [[tval]]
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
      <div ng-show="successfullsaved && !saveinprogress" class="alert alert-success">
        <i class="fa fa-check-circle"></i> {lang text="Sikeresen létrehozta a hirdetést."}
      </div>
      <div ng-show="saveinprogress" class="alert alert-warning">
        <i class="fa fa-spin fa-spinner"></i> {lang text="Létrehozás folyamatban. Kis türelmét kérjük."}
      </div>
      <button ng-show="!saveinprogress" class="btn btn-danger" ng-click="create()">{lang text="Hirdetés létrehozása"}</button>
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
})
{/literal}
</script>
