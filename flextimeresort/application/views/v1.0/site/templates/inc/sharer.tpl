<div class="sharing">
  <div class="title">
    {lang text="TETSZETT_MEGOSZTAS"}
  </div>
  <div class="shares">
    {assign var="url" value=$settings.page_url|cat:$smarty.server.REQUEST_URI}
    <div class="fb">
      <iframe src="https://www.facebook.com/plugins/share_button.php?href={$url}&layout=button_count&size=small&mobile_iframe=true&appId={$settings.FACEBOOK_APP_ID}&width=100&height=20" width="100" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    </div>
    <div class="googleplus">
      <div class="g-plus" data-action="share"></div>
    </div>
    <div class="email" data-toggle="tooltip" title="{lang text='MEGOSZTAS_EMAILBEN'}">
      <a href="mailto:?subject={$title}&body={$seo_desc}"><i class="fa fa-envelope"></i> {lang text="EMAIL"}</a>
    </div>
  </div>
</div>
