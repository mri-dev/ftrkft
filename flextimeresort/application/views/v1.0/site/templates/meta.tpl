<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
<!-- Bootstrap 4.0.0 -->
<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/dist/css/main.css">
<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="/plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="/plugins/iCheck/all.css">

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
<script src="/plugins/tinymce/tinymce.min.js"></script>
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="/plugins/angular-ui-tinymce/dist/tinymce.min.js"></script>
<script type="text/javascript" src="/dist/js/ang.app.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key={$settings.GOOGLE_API_KEY}&libraries=places"></script>
{if !$user}
<!-- iCheck -->
<link rel="stylesheet" href="/plugins/iCheck/square/blue.css">
{/if}
<script >
  window.___gcfg = {
    lang: 'hu-HU',
    parsetags: 'onload'
  };
</script>
<script src="https://apis.google.com/js/platform.js" async defer></script>

<script type="text/javascript">
  $(function(){
    var top_nav_position = $('header .top nav').position();
    $('header .top nav .helper').animate({
      left: top_nav_position.left + 100
    }, 0);

    $(window).resize(function(){
      top_nav_position = $('header .top nav').position();

      $('header .top nav .helper').animate({
        left: top_nav_position.left
      }, 0);
    });

    $('*[data-progresslineauto]').each(function(i,e){
      var perc = parseInt($(e).data('progresslineauto'));
      progressBindChange(e, perc);
    });

    $(window).click(function(event) {
      if (!$(event.target).closest('.toggler-opener').length) {
        $('.toggler-opener').removeClass('opened toggler-opener');
        $('*[tglwatcher].toggled').removeClass('toggled');
      }
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
      init_instance_callback: function (editor) {
        editor.on('Change', function (e) {
          var content = e.level.content;
          var id = e.target.id;
          var scope = angular.element($('#'+id)).scope();
          if (scope) {
            var ang_model = $('#'+id).data('angmodel');
            var ang_obj = $('#'+id).data('angobj');
            if (ang_model && ang_obj) {
              scope[ang_obj][ang_model] = content;
            }
          }
        });
      }
    });

    $('*[tglwatcher]').click(function(event){
       event.stopPropagation();
       event.preventDefault();
       var e = $(this);
       var target_id = e.attr('tglwatcher');
       var opened = e.hasClass('toggled');

       if(opened) {
         e.removeClass('toggled');
         $('#'+target_id).removeClass('opened toggler-opener');
       } else {
         e.addClass('toggled');
         $('#'+target_id).addClass('opened toggler-opener');
       }
     });

     $('.multiselect-list input[type=checkbox]').change(function(){
       var e = $(this);
       var key = e.data('key');


       var data_parent = $(this).data('parent');
       var val = $(this).val();
       var ch = $(this).is(':checked');


       if (!ch) {
         $('.multiselect-list input[type=checkbox][data-key=\''+key+'\'][data-parent='+val+']').parent().removeClass('show');
       } else {
         $('.multiselect-list input[type=checkbox][data-key=\''+key+'\'][data-parent='+val+']').parent().addClass('show');
         $('.multiselect-list input[type=checkbox][data-key=\''+key+'\'][data-parent='+val+']').prop('checked', false);
       }

      if (data_parent != '') {
          var dtparent = $('.multiselect-list input[type=checkbox][data-key=\''+key+'\'][data-id='+data_parent+']');
          var childchecked = $('.multiselect-list input[type=checkbox][data-key=\''+key+'\'][data-parent='+data_parent+']:checked').length;

          if (dtparent.is(':checked')) {
            dtparent.prop('checked', false);
          } else {
            if(childchecked == 0) {
              console.log(childchecked);
              $('.multiselect-list input[type=checkbox][data-key=\''+key+'\'][data-parent='+data_parent+']').parent().removeClass('show');
            }
          }
      }

      var selected = collect_checkbox(key, false);

       $('#'+key+'_ids').val(selected);
     });

     function progressBindChange(e, perc) {
       $(e).parent().css({
         width: perc+'%'
       });
       $(e).find('.percentvalue').text(perc);
     }

     function progressLineStatus(what, perc) {
       var e = $('*[data-progressline=\''+what+'\']');
       progressBindChange(e, perc);
     }

     function collect_checkbox(key, loader)
      {
        var arr = [];
        var str = [];
        var seln = 0;

        jQuery('#'+key+' input[type=checkbox]').each(function(e,i)
        {
          if(jQuery(this).is(':checked') && !jQuery(this).is(':disabled')){
            seln++;
            arr.push(jQuery(this).val());
            str.push(jQuery(this).next('label').text());
          }
        });

        if(seln <= 3 ){
          jQuery('input[tglwatcher=\''+key+'\']').val(str.join(", ")).attr('title', str.join(", "));
        } else {
          jQuery('input[tglwatcher=\''+key+'\']').val(seln + " db kiválasztva").attr('title', str.join(", "));
        }

        return arr.join(",");
      }
  })
</script>
