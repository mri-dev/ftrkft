<div class="ugyfelkapu-content-holder">
  <div class="page-width">
    <div class="row">
      <div class="col-md-9">
        <div class="inside">
          {if $subpage == ''}
            {include file=$template_root|cat:"user/ugyfelkapu/ertesites.tpl"}
          {else}
            {include file=$template_root|cat:"user/ugyfelkapu/"|cat:$subpage|cat:".tpl"}
          {/if}
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</div>
