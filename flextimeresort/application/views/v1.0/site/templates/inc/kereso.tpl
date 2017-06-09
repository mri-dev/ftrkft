<form class="" id="searcher" action="{$smarty.const.PATH_SEARCH}" method="get">
  <div class="input-holder">
    <div class="inp inp-keyword">
      <input type="text" name="s" class="form-control" placeholder="{lang text='KULCSSZO_SZERINT'}" value="">
    </div>
    <div class="inp inp-munkakor">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" class="form-control viewer" class="tglwatcher" tglwatcher="munkakor_multiselect" placeholder="{lang text='MINDEN_MUNKAKOR'}" readonly="readonly" value="">
          <input type="hidden" id="munkakor_multiselect_ids" name="mk" value="">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="munkakor_multiselect">
          <div class="selector-wrapper">
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="inp inp-telepules">
      <input type="text" id="search_place" name="c" class="form-control" placeholder="{lang text='MINDEN_TELEPULES'}" value="">
    </div>
    <div class="inp inp-tipus">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" data-toggle="tooltip" data-placement="top" title="" class="form-control viewer" class="tglwatcher" tglwatcher="tipus_multiselect" placeholder="{lang text='TIPUS'}" readonly="readonly" value="">
          <input type="hidden" id="tipus_multiselect_ids" name="t" value="">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="tipus_multiselect">
          <div class="selector-wrapper">
            <div class="selector-row lvl-0">
              <input type="checkbox" class="ccb" data-key="tipus_multiselect" value="1" id="cb1"> <label for="cb1">Egy</label>
            </div>
            <div class="selector-row lvl-0">
              <input type="checkbox" class="ccb" data-key="tipus_multiselect" value="2" id="cb2"> <label for="cb2">Kettő</label>
            </div>
            <div class="selector-row lvl-0">
              <input type="checkbox" class="ccb" data-key="tipus_multiselect" value="3" id="cb3"> <label for="cb3">Három</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="inp inp-kategoria">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" class="form-control viewer" class="tglwatcher" tglwatcher="kategoria_multiselect" placeholder="{lang text='KATEGORIA'}" readonly="readonly" value="">
          <input type="hidden" id="kategoria_multiselect_ids" name="k" value="">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="kategoria_multiselect">
          <div class="selector-wrapper">
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="inp inp-button-search">
      <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i> {lang text="KERESES"}</button>
    </div>
    <div class="inp inp-order">
      <div class="order-select-container">
        <div class="ico-prefix">
          <img src="{$smarty.const.IMG}icons/white/24h-recicle.svg" alt="Order">
        </div>
        <select class="form-control" name="o">
          <option value="new">{lang text="LEGUJABBAK"}</option>
        </select>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
{literal}
$(function(){
  var input = document.getElementById('search_place');
  var options = {
    types: ['(cities)'],
    componentRestrictions: {country: "hu"}
  };
  autocomplete = new google.maps.places.Autocomplete(input, options);
})
{/literal}

</script>
