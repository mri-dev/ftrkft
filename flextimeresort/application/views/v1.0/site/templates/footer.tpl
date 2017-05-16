</div> <!-- /.content-holder -->
<footer>
  <div class="top">
    <div class="page-width">
      <div class="row">
        <div class="col-md-3 logo">
          <a href="/"><img src="{$smarty.const.IMG}logo-single-horizontal-greenbg.svg" alt="{$settings.page_title|strip_tags}"></a>
        </div>
        <div class="col-md-9 slogan">
          <h4>{lang text="PAGESLOGAN"}</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="divider"></div>
  <div class="menu">
    <div class="page-width">
      <div class="row">
        <div class="col-md-9">
          <div class="buttons">
            <ul class="navi">
              <li><img src="{$smarty.const.IMG}icons/white/building.svg" class="" alt="{lang text="AJANLAT_FELTOLTESE_MUNKAADOKNAK"}"> <a href="#" class="btn btn-default btn-darker">{lang text="AJANLAT_FELTOLTESE_MUNKAADOKNAK"}</a></li>
              <li><img src="{$smarty.const.IMG}icons/white/user.svg" class="" alt="{lang text="ADATOK_FELTOLTESE_MUNKAVALLOKNAK"}"><a href="#" class="btn btn-success">{lang text="ADATOK_FELTOLTESE_MUNKAVALLOKNAK"}</a></li>
            </ul>
          </div>
          <div class="divider"></div>
          <nav>
            <ul>
              <li><a href="#">Kapcsolat</a></li>
              <li><a href="#">Kapcsolat</a></li>
            </ul>
            <ul>
              <li class="header">Munkavállalóknak</li>
              <li><a href="#">Kapcsolat</a></li>
              <li><a href="#">Kapcsolat</a></li>
            </ul>
            <div class="clearfix"></div>
          </nav>
        </div>
        <div class="col-md-3">
          <div class="contacts">
            <h4>{lang text="ELERHETOSEGUNK"}:</h4>
            <div>{$settings.address}</div>
            <div><strong>{lang text="TELEFONSZAM"}:</strong> <a href="tel:{$settings.phone}">{$settings.phone}</a></div>
            <div><strong>{lang text="EMAIL"}:</strong> <a href="mailto:{$settings.email}">{$settings.email}</a></div>
            <ul class="navi">
              <li class="social facebook">
                <a href="#"><i class="fa fa-facebook"></i></a>
              </li>
              <li class="social googleplus">
                <a href="#"><i class="fa fa-google-plus"></i></a>
              </li>
              <li class="social twitter">
                <a href="#"><i class="fa fa-twitter"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright">
    <div class="page-width">
      Copyright &copy; 2010 - {$smarty.const.NOW|date_format:"%Y"} &nbsp; <strong>{$settings.page_title}</strong>
    </div>
  </div>
</footer>
</body>
</html>
