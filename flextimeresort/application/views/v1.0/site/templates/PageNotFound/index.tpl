<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="page-width">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i> {lang text="FOOLDAL"}</a></li>
      <li class="active breadcrumb-item">{lang text="A_KERESETT_OLDAL_NEM_TALALHATO"}</li>
    </ol>
    <div class="clearfix"></div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="page-width">
    <div class="error-page">
      <h2 class="headline"> 404</h2>

      <div class="error-content">
      	<br>
      	<h3><i class="fa fa-warning"></i> {lang text="A_KERESETT_OLDAL_NEM_TALALHATO"}</h3>

        <p>
          {lang text="404_ERROR_LEIRAS" oldalurl=$settings.page_url|cat:$smarty.server.REQUEST_URI}
        </p>

      </div>
      <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
  </div>
</section>
<!-- /.content -->
