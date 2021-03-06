<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
<!-- Bootstrap 4.0.0 -->
<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="/dist/css/main.css">
<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="/plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="/plugins/iCheck/all.css">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/plugins/fancybox/jquery.fancybox.css"/>
<link rel="stylesheet" type="text/css" href="/plugins/fancybox/helpers/jquery.fancybox-buttons.css" />
<link rel="stylesheet" href="{$admin_css}">

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Tether -->
<script src="/plugins/tether/dist/js/tether.min.js"></script>
<!-- Bootstrap 4.0.0 -->
<script src="/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/app.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="/plugins/chartjs/Chart.min.js"></script>
<script src="https://api.trello.com/1/client.js?key={$settings.TRELLO_API_KEY}&token=ba5ebbfb5b58922a170b0f26e26c2088ead945cbba9d570d88c490513a86b76e"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- iCheck -->
<script src="/plugins/iCheck/icheck.min.js"></script>
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="/plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="/plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="/plugins/fancybox/helpers/jquery.fancybox-buttons.js"></script>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>

<script src="/plugins/angular-ui-tinymce/dist/tinymce.min.js"></script>
<script src="/dist/js/angular-modules/angular-nl2br.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.js"></script>
<script type="text/javascript" src="/dist/js/ang.app.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key={$settings.GOOGLE_API_KEY}&libraries=places"></script>

{if !$user}
<!-- iCheck -->
<link rel="stylesheet" href="/plugins/iCheck/square/blue.css">
{/if}
<script type="text/javascript">
  var closedSidebar = localStorage.getItem('closedSidebar');

  $(function(){
    watchNotifies();
    $('[data-toggle="tooltip"]').tooltip();
    $('table.datatable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Hungarian.json"
        },
        "pageLength": 50
    });

    $('.iframe-btn').fancybox({
  		maxWidth	: 800,
  		maxHeight	: 600,
  		fitToView	: false,
  		width		: '70%',
  		height		: '70%',
  		autoSize	: false,
  		closeClick	: false,
  		openEffect	: 'none',
  		closeEffect	: 'none',
  		closeBtn 	: false,
  		padding		: 0
    });

    tinymce.init({
      selector: "textarea.editor",
      editor_deselector : 'no-editor',
      theme: "modern",
		  language: "hu_HU",
      image_advtab: true ,
		  theme_advanced_resizing : true,
      plugins: [
		         "advlist autolink link image lists charmap print preview hr anchor pagebreak autoresize",
		         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
		         "table contextmenu directionality emoticons paste textcolor fullscreen code"
		   ],
		  toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		  toolbar2: " | link unlink anchor | image media |  print preview code ",
    });

    if(closedSidebar != null) {
      $('body').addClass('sidebar-toggled');
    }
    $('#pagepreloader').hide(0);

    $('a.sidebar-toggle').click(function(){
      var toggled = $('body').hasClass('sidebar-toggled');

      if (toggled) {
        $('body').removeClass('sidebar-toggled');
        localStorage.removeItem('closedSidebar');
      } else {
        $('body').addClass('sidebar-toggled');
        localStorage.setItem('closedSidebar', 1);
      }
    });
  })

  function watchNotifies() {
    $.post('/ajax/get/',
    {
      type: 'adminotify'
    },function(d){
        console.log(d);
      if (d.unwatched_messages == 0) {
        $('.unwatched_messages_ntf').hide(0);
      } else{
        $('.unwatched_messages_ntf').show(0).text(d.unwatched_messages);
      }

      if (d.waiting_ad_applicant == 0) {
        $('.waiting_ad_applicant_ntf').hide(0);
      } else{
        $('.waiting_ad_applicant_ntf').show(0).text(d.waiting_ad_applicant);
      }

      if (d.waiting_userequest__ntf == 0) {
        $('.waiting_userequest__ntf').hide(0);
      } else{
        $('.waiting_userequest__ntf').show(0).text(d.waiting_userequest__ntf);
      }
    },
      "json"
    );

    setTimeout(function(){
      watchNotifies();
    }, 2500);
  }
</script>
