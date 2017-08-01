<div class="header">
  <h2>{lang text="Munkavállalók keresése"}</h2>
</div>
<form class="" id="searcher" action="{$settings.munkavallalo_search_slug}" method="get">
  <div class="input-holder for-munkavallalok">
    <div class="inp inp-munkakor">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" data-toggle="tooltip" data-placement="top" class="form-control viewer" class="tglwatcher" tglwatcher="munkakor_multiselect" placeholder="{lang text='Minden munkaterület'}" readonly="readonly" value="">
          <input type="hidden" id="munkakor_multiselect_ids" name="mk" value="{$smarty.get.mk}">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="munkakor_multiselect">
          <div class="selector-wrapper">
            {while $munkakorok->walk()}
            <div class="selector-row lvl-{$munkakorok->getDeep()}" style="{if $munkakorok->getDeep() !=0}display:none !important;{/if}">
              <input type="checkbox" class="ccb" data-lvl="{$munkakorok->getDeep()}" data-id="{$munkakorok->getID()}" {if in_array($munkakorok->getID(), $selected_munkakor)}checked="checked"{/if} data-parent="{$munkakorok->getParentID()}" data-key="munkakor_multiselect" value="{$munkakorok->getID()}" id="munkakor_cb_{$munkakorok->getID()}"> <label for="munkakor_cb_{$munkakorok->getID()}">{$munkakorok->getName()}</label>
            </div>
            {/while}
            {if $munkakorok->Count() == 0}
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
            {/if}
          </div>
        </div>
      </div>
    </div>
    <div class="inp inp-tapasztalat">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" data-toggle="tooltip" data-placement="top" title="" class="form-control viewer" class="tglwatcher" tglwatcher="munkatapasztalat_multiselect" placeholder="{lang text='Tapasztalat'}" readonly="readonly" value="">
          <input type="hidden" id="munkatapasztalat_multiselect_ids" name="mt" value="{$smarty.get.mt}">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="munkatapasztalat_multiselect">
          <div class="selector-wrapper">
            {while $munkatapasztalat->walk()}
            <div class="selector-row lvl-{$munkatapasztalat->getDeep()}">
              <input type="checkbox" class="ccb" data-lvl="{$munkatapasztalat->getDeep()}" data-id="{$munkatapasztalat->getID()}" {if in_array($munkatapasztalat->getID(), $selected_munkatapasztalat)}checked="checked"{/if} data-parent="{$munkatapasztalat->getParentID()}" data-key="munkatapasztalat_multiselect" value="{$munkatapasztalat->getID()}" id="tipus_cb_{$munkatapasztalat->getID()}"> <label for="tipus_cb_{$munkatapasztalat->getID()}">{$munkatapasztalat->getName()}</label>
            </div>
            {/while}
            {if $munkatapasztalat->Count() == 0}
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
            {/if}
          </div>
        </div>
      </div>
    </div>
    <div class="inp inp-vegzettsegszint">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" data-toggle="tooltip" data-placement="top" title="" class="form-control viewer" class="tglwatcher" tglwatcher="legmagasabbvegzettseg_multiselect" placeholder="{lang text='Vézettségi szint'}" readonly="readonly" value="">
          <input type="hidden" id="legmagasabbvegzettseg_multiselect_ids" name="lv" value="{$smarty.get.lv}">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="legmagasabbvegzettseg_multiselect">
          <div class="selector-wrapper">
            {while $legmagasabbvegzettseg->walk()}
            <div class="selector-row lvl-{$legmagasabbvegzettseg->getDeep()}">
              <input type="checkbox" class="ccb" data-lvl="{$legmagasabbvegzettseg->getDeep()}" data-id="{$legmagasabbvegzettseg->getID()}" {if in_array($legmagasabbvegzettseg->getID(), $selected_legmagasabbvegzettseg)}checked="checked"{/if} data-parent="{$legmagasabbvegzettseg->getParentID()}" data-key="legmagasabbvegzettseg_multiselect" value="{$legmagasabbvegzettseg->getID()}" id="tipus_cb_{$legmagasabbvegzettseg->getID()}"> <label for="tipus_cb_{$legmagasabbvegzettseg->getID()}">{$legmagasabbvegzettseg->getName()}</label>
            </div>
            {/while}
            {if $legmagasabbvegzettseg->Count() == 0}
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
            {/if}
          </div>
        </div>
      </div>
    </div>

    <div class="inp inp-megye">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" data-toggle="tooltip" data-placement="top" title="" class="form-control viewer" class="tglwatcher" tglwatcher="munkamegye_multiselect" placeholder="{lang text='Minden megye'}" readonly="readonly" value="">
          <input type="hidden" id="munkamegye_multiselect_ids" name="megye" value="{$smarty.get.megye}">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="munkamegye_multiselect">
          <div class="selector-wrapper">
            {while $munkamegye->walk()}
            <div class="selector-row lvl-{$munkamegye->getDeep()}">
              <input type="checkbox" class="ccb" data-lvl="{$munkamegye->getDeep()}" data-id="{$munkamegye->getID()}" {if in_array($munkamegye->getID(), $selected_munkamegye)}checked="checked"{/if} data-parent="{$munkamegye->getParentID()}" data-key="munkamegye_multiselect" value="{$munkamegye->getID()}" id="tipus_cb_{$munkamegye->getID()}"> <label for="tipus_cb_{$munkamegye->getID()}">{$munkamegye->getName()}</label>
            </div>
            {/while}
            {if $munkamegye->Count() == 0}
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
            {/if}
          </div>
        </div>
      </div>
    </div>

    <div class="inp inp-nem">
      <div class="multiselect-list">
        <div class="value-viewer">
          <input type="text" data-toggle="tooltip" data-placement="top" title="" class="form-control viewer" class="tglwatcher" tglwatcher="nem_multiselect" placeholder="{lang text='Férfi / Nő'}" readonly="readonly" value="">
          <input type="hidden" id="nem_multiselect_ids" name="nem" value="{$smarty.get.nem}">
          <div class="helper">
            <i class="fa fa-angle-down"></i>
          </div>
        </div>
        <div class="multi-selector-holder" id="nem_multiselect">
          <div class="selector-wrapper">
            {while $nem->walk()}
            <div class="selector-row lvl-{$nem->getDeep()}">
              <input type="checkbox" class="ccb" data-lvl="{$nem->getDeep()}" data-id="{$nem->getID()}" {if in_array($nem->getID(), $selected_nem)}checked="checked"{/if} data-parent="{$nem->getParentID()}" data-key="nem_multiselect" value="{$nem->getID()}" id="tipus_cb_{$nem->getID()}"> <label for="tipus_cb_{$nem->getID()}">{$nem->getName()}</label>
            </div>
            {/while}
            {if $nem->Count() == 0}
            <div class="no-input">
              {lang text="NINCS_MULTISELECT_INPUT"}
            </div>
            {/if}
          </div>
        </div>
      </div>
    </div>

    <div class="inp inp-button-search">
      <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i> {lang text="KERESES"}</button>
    </div>
  </div>
</form>
<script type="text/javascript">
{literal}
$(function(){
  collect_checkbox('munkakor_multiselect', true);
  collect_checkbox('munkatapasztalat_multiselect', true);
  collect_checkbox('legmagasabbvegzettseg_multiselect', true);
  collect_checkbox('munkamegye_multiselect', true);
  collect_checkbox('nem_multiselect', true);

  var input = document.getElementById('search_place');
  var options = {
    types: ['(cities)'],
    componentRestrictions: {country: "hu"}
  };
  autocomplete = new google.maps.places.Autocomplete(input, options);

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      $('#search_place').val(place.address_components[0].long_name);
  });
})
{/literal}
</script>
