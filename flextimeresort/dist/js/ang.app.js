// Ügyfélkapu profil módosító modul
var pm = angular.module("profilModifier", [], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

pm.service('fileUploadService', function($http, $q){
  this.uploadFileToUrl = function (file, uploadUrl, callback) {
      var fileFormData = new FormData();
      fileFormData.append('file', file);
      var deffered = $q.defer();

      $http({
        method: 'POST',
        url: uploadUrl,
        params: {
          type: 'uploadProfilImg'
        },
        data: fileFormData,
        headers: {
           'Content-Type': undefined
        },
      }).then(function successCallback(response) {
        callback(response.data);
      }, function errorCallback(response) {
        callback(response.data);
      });
  }
});

pm.controller("formValidor",['$scope', '$http', '$timeout', 'fileUploadService',  function($scope, $http, $timeout, fileUploadService) {
  $scope.default = {};
  $scope.form = {};
  $scope.terms = {};
  $scope.selectedlist = {};
  $scope.listtgl = {};
  $scope.step = '';
  $scope.dataloaded = false;
  $scope.successfullsaved = false;
  $scope.saveinprogress = false;
  $scope.cansavenow = true;
  $scope.texthintfocusstyle = {top: '42px', opacity: 1, filter: 'alpha(opacity=100)'};
  $scope.profilpreview = '/dist/img/profil-select-default.svg';
  $scope.profilselected = false;
  $scope.profilimguploadprogress = false;
  $scope.allowProfilType = ['jpg', 'jpeg', 'png'];
  $scope.selectedprofilimg = {
    size: 0,
    type: null
  };
  $scope.multiparam = [];
  $scope.multiparam_group_deletting = [];

  $scope.fromgroup = {
    alap: ['name', 'email', 'nem', 'allampolgarsag', 'csaladi_allapot', 'anyanyelv', 'iskolai_vegzettseg']
  }

  $scope.tglList = function(l){
    angular.forEach($scope.fromgroup, function(v, k){
      angular.forEach(v, function(v2, k2){
        if(  v2 != l) {
          $scope.listtgl[v2] = false;
        }
      });
    });

    if ($scope.listtgl[l]) {
      $scope.listtgl[l] = false;
    } else {
      $scope.listtgl[l] = true;
    }
  }

  $scope.settings = function(page){
    $scope.step = page;
  }

  $scope.validateUserData = function(user) {
    //console.log(user);
    // Pass
    $scope.form.name = user.alap.name;
    $scope.form.email = user.alap.email;
    $scope.form.szuletesi_datum = user.alap.szuletesi_datum;
    $scope.profilpreview = user.alap.profil_kep;

    $scope.form.telefon = user.elerhetoseg.telefon;
    $scope.form.lakcim_irsz = user.elerhetoseg.lakcim_irsz;
    $scope.form.lakcim_city = user.elerhetoseg.lakcim_city;
    $scope.form.lakcim_uhsz = user.elerhetoseg.lakcim_uhsz;
    $scope.form.social_url_facebook = user.elerhetoseg.social_url_facebook;
    $scope.form.social_url_twitter = user.elerhetoseg.social_url_twitter;
    $scope.form.social_url_linkedin = user.elerhetoseg.social_url_linkedin;

    // List
    var termcicle = 0;
    angular.forEach($scope.terms, function(v, k){
      var ld = $scope.terms[k][':'+user.terms[k]];
      termcicle++;

      if (typeof ld !== 'undefined') {
        $scope.form[k] = user.terms[k];
        $scope.selectedlist[k] = {
          id: ld.id,
          text: ld.value
        };
      }
    });

    var modulparams = user.moduls;
    if (modulparams) {
      angular.forEach(modulparams, function(modulstack, profilpage){
        angular.forEach(modulstack, function(moduldata, modulkey){
          angular.forEach(moduldata, function(data, ix){
            if (typeof $scope.multiparam[modulkey] == 'undefined') {
              $scope.multiparam[modulkey] = [];
            }
            $scope.multiparam[modulkey].push($scope.prepareRAWModulData(modulkey, data));
          });
        });
      });
    }

    if(termcicle == 0) {
      window.location.reload();
    }
  };

  $scope.defaultModulData = function(modulkey){
    switch (modulkey) {
      case 'vegzettseg':
        var data = {
          vegzettseg_szint: 0,
          szakirany: 0,
          intezmeny: '',
          keszsegek: '',
          folyamatban: false,
          startdate: {
            year: parseInt(new Date().getFullYear()),
            month: 1
          },
          enddate: {
            year: parseInt(new Date().getFullYear()),
            month: 1
          },
          grouphash: false
        };
      break;
      default:
        var data = {
          grouphash: false
        };
      break;
    }

    return data;
  }

  $scope.prepareRAWModulData = function(modulkey, raw){
    switch (modulkey) {
      case 'vegzettseg':
        var data = angular.extend($scope.defaultModulData(modulkey), {
          vegzettseg_szint: raw.vegzettseg_szint.value,
          szakirany: raw.szakirany.value,
          intezmeny: raw.intezmeny.value,
          keszsegek: raw.keszsegek.value,
          folyamatban: (raw.folyamatban.value == 0) ? false : true,
          startdate: {
            year: raw.startdate.year.value,
            month: raw.startdate.month.value
          },
          enddate: {
            year: raw.enddate.year.value,
            month: raw.enddate.month.value
          },
          grouphash: raw.grouphash
        });
      break;
    }
    return data;
  }

  $scope.uploadProfil = function(callback){
    var file = $scope.fileinput;
    var uploadUrl = "/ajax/data/", //Url 1of webservice/api/server
        promise = fileUploadService.uploadFileToUrl(file, uploadUrl, function(re){
          callback(re);
        });
  }

  $scope.selectListValue = function(key, id, text) {
    $scope.selectedlist[key] = {
      id: id,
      text: text
    };
    $scope.form[key] = id;
    $scope.listtgl[key] = false;
  }

  // Lista letöltése
  $http({
    method: 'POST',
    url: '/ajax/data',
    params: {
      type: 'lists',
      lists: 'nem,megyek,allampolgarsag,csaladi_allapot,anyanyelv,iskolai_vegzettsegi_szintek,honapok,tanulmany_szakirany'
    }
  }).then(function successCallback(response) {
    var d = response.data;

    angular.forEach(d.lists, function(v, k){
      $scope.terms[v.termkey] = d.terms[v.termkey];
    });
  }, function errorCallback(response) {});

  // Felhasználó adatok
  $http({
    method: 'POST',
    url: '/ajax/data',
    params: {
      type: 'me'
    }
  }).then(function successCallback(response) {
    var d = response.data;
    $scope.dataloaded = true;
    $scope.validateUserData(d);
  }, function errorCallback(response) {});

  $scope.save = function(next){
    $scope.saveinprogress = true;

    if ($scope.fileinput) {
      $scope.profilimguploadprogress = true;
    }

    $scope.uploadProfil(function(re){
      if ($scope.fileinput) {
        $scope.profilimguploadprogress = false;
      }

      if(re.uploaded_path){
        $scope.form.newprofilimg = re.uploaded_path;
      }

      var multiparamdata = {};
      angular.extend(multiparamdata,$scope.multiparam);

      var delete_modul_group = {};
      angular.extend(delete_modul_group, $scope.multiparam_group_deletting);

      // Felhasználó adatok mentése
      $http({
        method: 'POST',
        url: '/ajax/data',
        params: {
          type: 'profilsave',
          form: $scope.form,
          moduldatas: multiparamdata,
          moduldelete: delete_modul_group,
          page: $scope.step
        }
      }).then(function successCallback(response) {
        $scope.saveinprogress = false;
        $scope.successfullsaved = true;

        var d = response.data;
        console.log(d);
        if(next) {
          document.location = '/ugyfelkapu/profil/'+d.nextpage;
        } else {
          $timeout(function(){
            $scope.successfullsaved = false;
          }, 3000);
        }
      }, function errorCallback(response) {});
    });
  }

  $scope.modulVariableRemover = function(key, index, storedgrouphash){
    $scope.multiparam[key].splice(index, 1);
    if (storedgrouphash) {
      $scope.multiparam_group_deletting.push(storedgrouphash);
    }
  }

  $scope.newModulVariable = function(key) {

    if (typeof $scope.multiparam[key] === 'undefined') {
        $scope.multiparam[key] = [];
        $scope.multiparam[key].push($scope.defaultModulData(key));
    } else {
      $scope.multiparam[key].push($scope.defaultModulData(key));
    }

    console.log($scope.multiparam);
  }


  $scope.yearGenerator = function(min_year){
    var years = new Array();
    var cyear = parseInt(new Date().getFullYear());
    var tyear = (typeof min_year === 'undefined') ? cyear - 80: min_year;

    for (var i = cyear; i >= tyear; i--) {
      years.push(i);
    }

    return years;
  }
}])
.directive('fileModel', ['$parse', function ($parse) {
  return {
    link: function(scope, element, attributes) {
      element.bind("change", function(changeEvent) {
        scope.fileinput = changeEvent.target.files[0];

        var ext = scope.fileinput.name.split('.').pop().toLowerCase();
        var correct_ext = scope.allowProfilType.indexOf(ext) > -1

        scope.selectedprofilimg.type = ext;
        scope.selectedprofilimg.size = scope.fileinput.size / 1024;

        if(correct_ext) {
          var reader = new FileReader();
          reader.onload = function(loadEvent) {
            scope.$apply(function() {
              scope.profilselected = true;
              scope.profilpreview = loadEvent.target.result;
            });
          }
          reader.readAsDataURL(scope.fileinput);
          scope.cansavenow = true;
        } else {
          scope.cansavenow = false;
        }

        if(scope.selectedprofilimg.size > 2024) {
          scope.cansavenow = false;
        } else {
          if(correct_ext){
            scope.cansavenow = true;
          }
        }
      });
    }
  }
 }])
 .directive('profilModul', function(){
   return {
    restrict: 'E',
    replace: true,
    templateUrl: function(e,a){
      return 'modulview/'+a.group+'/'+a.item;
    },
    scope: {
      mpkey: '@'
    },
    link: function(s, e, a){
      s.newModulVariable = function(mpkey){
          return s.$parent.newModulVariable(mpkey);
      }
      s.yearGenerator = function(y){
          return s.$parent.yearGenerator(y);
      }
      s.modulVariableRemover = function(a, b, c){
          return s.$parent.modulVariableRemover(a, b, c);
      }

      s.multiparam = s.$parent.multiparam;
      s.terms = s.$parent.terms;
    }
   }
 });

/**
* Ügyfélkapu üzenetváltó modul
**/
var msg = angular.module("UserMessanger", ['nl2br', 'ngSanitize'], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

msg.controller( "MessagesList", ['$scope', '$http', '$timeout', function($scope, $http, $timeout)
{
  $scope.is_msg = false;
  $scope.unreaded_messages = {
    inbox: 0,
    outbox: 0
  };
  $scope.data_loaded = false;
  $scope.messages = {};
  $scope.result = {};
  $scope.newnoticemsg = {};
  $scope.msgtgl = {};
  $scope.newmsg_left_length = 1000;
  $scope.newmsg = null;
  $scope.newmsg_focused = false;
  $scope.newmsg_send_progress = false;
  $scope.newmsgerrmsg=false;
  $scope.syncMsgTimeout = null;
  $scope.syncCount = 0;

  // Init messanger
  $scope.init = function(group, is_msg, uid, session){
    $scope.is_msg = is_msg;
    $scope.loadMessages(group);

    if (is_msg) {
      $scope.MessageSessionActions(session);
    }

  }

  $scope.MessageSessionActions = function(session){
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'messanger_message_viewed',
        session: session,
        by: 'user_readed_at'
      }
    }).then(
      function successCallback(response) {},
      function errorCallback(response) {});
  }

  $scope.archiveMessageSession = function(session, admin){
    if (admin) {
      $scope.saveMsgSessionData(session, 'archived_by_admin', 1);
    } else {
      $scope.saveMsgSessionData(session, 'archived_by_user', 1);
    }
  }

  $scope.saveMsgSessionData = function(session, record, value){
    $scope.newnoticemsg[session] = false;

    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'messanger_messagesession_edit',
        session: session,
        what: record,
        value: value
      }
    }).then(function successCallback(response) {
      var d = response.data;

      if (d.success) {
        $scope.msgtgl[session] = false;
      } else {
        $scope.newnoticemsg[d.session] = d.msg;
      }
    }, function errorCallback(response) {});
  }

  $scope.sendMessage = function(session, from_id, to_id, admin){
    if($scope.is_msg) {
      if(!$scope.newmsg_send_progress) {
        $scope.newmsg_send_progress = true;
        $scope.newmsgerrmsg = false;
        // Üzenetek küldése
        $http({
          method: 'POST',
          url: '/ajax/data',
          params: {
            type: 'messanger_message_send',
            session: session,
            msg: $scope.newmsg,
            from: from_id,
            to: to_id,
            admin: admin
          }
        }).then(function successCallback(response) {
          var d = response.data;
          $scope.newmsg_send_progress = false;

          if (d.success) {
            $scope.syncMessages();
            $scope.newmsg = null;
            $scope.newmsg_focused=true;
          } else {
            $scope.newmsgerrmsg = d.msg;
          }

        }, function errorCallback(response) {});
      }
    }
  }

  $scope.syncMessages = function(){
    $scope.loadMessages();
  }

  $scope.loadMessages = function(type){
    // Üzenetek betöltése
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'messanger_messages',
        by: type
      }
    }).then(function successCallback(response) {
      var d = response.data;
      $scope.result = d;
      $scope.unreaded_messages = d.unreaded;
      $scope.messages = d.messages.list;
      $scope.data_loaded = true;

      if ($scope.syncCount <= 1000) {
        $timeout.cancel($scope.syncMsgTimeout);
        $scope.syncMsgTimeout = $timeout(function() {
          $scope.syncCount++;
          $scope.syncMessages();
        }, 5000);
      }

    }, function errorCallback(response) {});
  }
}]);

msg.directive('focusMe', function($timeout) {
  return {
    scope: { trigger: '=focusMe' },
    link: function(scope, element) {
      scope.$watch('trigger', function(value) {
        if(value === true) {
          //console.log('trigger',value);
          //$timeout(function() {
            element[0].focus();
            scope.trigger = false;
          //});
        }
      });
    }
  };
});

/**
* Admin ügyfélkapu üzenetváltó modul
**/
var admmsg = angular.module("AdminMessanger", ['nl2br', 'ngSanitize'], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

admmsg.controller( "MessagesList", ['$scope', '$http', '$timeout', '$window', function($scope, $http, $timeout, $window)
{
  $scope.is_msg = false;
  $scope.unreaded_messages = {
    inbox: 0,
    outbox: 0
  };
  $scope.data_loaded = false;
  $scope.messages = {};
  $scope.result = {};
  $scope.newnoticemsg = {};
  $scope.msgtgl = {};
  $scope.newmsg_left_length = 1000;
  $scope.newmsg = null;
  $scope.newmsg_focused = false;
  $scope.newmsg_send_progress = false;
  $scope.newmsgerrmsg=false;
  $scope.syncMsgTimeout = null;
  $scope.syncCount = 0;

  // Init messanger
  $scope.init = function(group, is_msg, uid, session){
    $scope.is_msg = is_msg;
    $scope.loadMessages(group);

    if (is_msg) {
      $scope.MessageSessionActions(session);
    }

  }

  $scope.MessageSessionActions = function(session){
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'messanger_message_viewed',
        session: session,
        by: 'admin_readed_at'
      }
    }).then(
      function successCallback(response) {},
      function errorCallback(response) {});
  }

  $scope.archiveMessageSession = function(session, admin){
    if (admin == 1) {
      $scope.saveMsgSessionData(session, 'archived_by_admin', 1);
    } else {
      $scope.saveMsgSessionData(session, 'archived_by_user', 1);
    }
  }

  $scope.saveMsgSessionData = function(session, record, value){
    $scope.newnoticemsg[session] = false;

    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'messanger_messagesession_edit',
        session: session,
        what: record,
        value: value
      }
    }).then(function successCallback(response) {
      var d = response.data;

      if (d.success) {
        $scope.msgtgl[session] = false;
      } else {
        $scope.newnoticemsg[d.session] = d.msg;
      }
    }, function errorCallback(response) {});
  }

  $scope.sendMessage = function(session, from_id, to_id, admin){
    if($scope.is_msg) {
      if(!$scope.newmsg_send_progress) {
        $scope.newmsg_send_progress = true;
        $scope.newmsgerrmsg = false;
        // Üzenetek küldése
        $http({
          method: 'POST',
          url: '/ajax/data',
          params: {
            type: 'messanger_message_send',
            session: session,
            msg: $scope.newmsg,
            from: from_id,
            to: to_id,
            admin: admin
          }
        }).then(function successCallback(response) {
          var d = response.data;
          $scope.newmsg_send_progress = false;

          if (d.success) {
            $scope.syncMessages();
            $scope.newmsg = null;
            $scope.newmsg_focused=true;
          } else {
            $scope.newmsgerrmsg = d.msg;
          }

        }, function errorCallback(response) {});
      }
    }
  }

  $scope.syncMessages = function(){
    $scope.loadMessages();
  }

  $scope.loadMessages = function(type){
    // Üzenetek betöltése
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'messanger_messages',
        by: type,
        for: 'admin',
        getstr: $window.location.search.substring(1)
      }
    }).then(function successCallback(response) {
      var d = response.data;
      $scope.result = d;
      $scope.unreaded_messages = d.unreaded;
      $scope.messages = d.messages.list;
      $scope.data_loaded = true;

      if ($scope.syncCount <= 1000) {
        $timeout.cancel($scope.syncMsgTimeout);
        $scope.syncMsgTimeout = $timeout(function() {
          $scope.syncCount++;
          $scope.syncMessages();
        }, 5000);
      }

    }, function errorCallback(response) {});
  }
}]);

admmsg.directive('focusMe', function($timeout) {
  return {
    scope: { trigger: '=focusMe' },
    link: function(scope, element) {
      scope.$watch('trigger', function(value) {
        if(value === true) {
          //console.log('trigger',value);
          //$timeout(function() {
            element[0].focus();
            scope.trigger = false;
          //});
        }
      });
    }
  };
});

/**
* Hirdetés létrehozó
**/
var ads = angular.module("Ads", ['ui.tinymce'], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

ads.controller( "Request", ['$scope', '$http', '$timeout', function($scope, $http, $timeout){
  $scope.inprogress = false;
  $scope.not_requested = true;

  $scope.requestAd = function(adid){
    $scope.inprogress = true;
    // Felhasználó adatok
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'adsrequest',
        id: adid
      }
    }).then(function successCallback(response) {
      console.log(response.data);
      var d = response.data;
      $scope.inprogress = false;
      $scope.not_requested = false;

      if (d.success) {
        $timeout(function(){
          window.location.reload(true);
        }, 3000);
      }
    }, function errorCallback(response) {});
  }
}]);

ads.controller( "Creator", ['$scope', '$http', '$timeout', function($scope, $http, $timeout)
{
  $scope.settings = {};
  $scope.terms = {};
  $scope.listtgl = {};
  $scope.allas = {};
  $scope.loaded_allas = {};
  $scope.selectedlist = {};
  $scope.userdata = {};
  $scope.term_list = {};
  $scope.tematics = [];
  $scope.form = {};

  $scope.cansavenow = true;
  $scope.saveinprogress = false;
  $scope.successfullsaved = false;
  $scope.dataloaded = false;
  $scope.editing_data_loaded = false;
  $scope.creator_in_progress = false;
  $scope.creator_saved = false;
  $scope.creator_created = false;
  $scope.creator_error_msg = false;

  $scope.short_desc_length = 150;
  $scope.keywords_length = 100;

  $scope.tinymceOptions = {
    skin: 'clear',
    language: "hu_HU",
    element_format : 'html',
    theme_advanced_resizing : true,
    entity_encoding : "raw",
    plugins: [
     "advlist autolink link lists charmap preview hr anchor pagebreak autoresize",
     "searchreplace wordcount visualblocks visualchars insertdatetime nonbreaking",
     "table contextmenu directionality emoticons paste textcolor fullscreen code"
    ],
    toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | link unlink anchor|preview code",
  };


  $scope.init = function(admin, authorid, edit_ad_id)
  {
    $scope.settings.admin = (admin == 0) ? false : true;

    $scope.allas.author_id = authorid;
    $scope.allas.created_by_admin = admin;
    $scope.settings.edit_ad_id = edit_ad_id;

    // Felhasználó adatok
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'user',
        id: authorid
      }
    }).then(function successCallback(response) {
      $scope.userdata = response.data;
      //console.log(response.data);
    }, function errorCallback(response) {});

    // Lista letöltése
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'lists'
      }
    }).then(function successCallback(response) {
      var d = response.data;
      $scope.term_list = d.lists;
      angular.forEach(d.lists, function(v, k){
        $scope.terms[v.termkey] = d.terms[v.termkey];
      });
      $scope.dataloaded = true;
    }, function errorCallback(response) {});

    if ($scope.settings.edit_ad_id != 0) {
      // Ajánlat adatok betöltése
      $http({
        method: 'POST',
        url: '/ajax/data',
        params: {
          type: 'getad',
          userid: 'me',
          adid: $scope.settings.edit_ad_id
        }
      }).then(function successCallback(response) {
        var d = response.data;
        console.log(d);
        if (d.success) {
          $scope.loaded_allas = d.data;
          $scope.prepareEditAdToView();
        }
      }, function errorCallback(response) {});
    }
  }

  $scope.prepareEditAdToView = function(){
    $scope.selectListValue('hirdetes_tipus', parseInt($scope.loaded_allas.hirdetes_tipus, false), $scope.loaded_allas.tipus_name);
    $scope.selectListValue('hirdetes_kategoria', parseInt($scope.loaded_allas.hirdetes_kategoria, false), $scope.loaded_allas.cat_name);
    $scope.selectListValue('megye_id', parseInt($scope.loaded_allas.megye_id, false), $scope.loaded_allas.megye_name);
    $scope.allas.pre_content = $scope.loaded_allas.pre_content;
    $scope.allas.content = $scope.loaded_allas.content;
    $scope.allas.city = $scope.loaded_allas.city;
    $scope.allas.short_desc = $scope.loaded_allas.short_desc;
    $scope.allas.active = ($scope.loaded_allas.active == '1') ? true : false;
    if ($scope.loaded_allas.short_desc) {
      $scope.short_desc_length = 150 - $scope.loaded_allas.short_desc.length;
    }
    $scope.allas.keywords = $scope.loaded_allas.keywords;
    if ($scope.loaded_allas.keywords) {
      $scope.keywords_length = 100 - $scope.loaded_allas.keywords.length;
    }
    $scope.allas.author_name = $scope.loaded_allas.author_name;
    $scope.allas.author_phone = $scope.loaded_allas.author_phone;
    $scope.allas.author_email = $scope.loaded_allas.author_email;
    if($scope.loaded_allas.munkakorok){
      $scope.allas.munkakorok = $scope.loaded_allas.munkakorok;
      angular.forEach($scope.allas.munkakorok, function(v,k){
        $scope.selectListValue('munkakorok', v.value, v.value_text, true);
      });
    }

    // Tematikus lista
    angular.forEach($scope.loaded_allas.term_list, function(v,k){
      // v.term_ids
      var selectedValues = [];
      angular.forEach(v.term_ids, function(k){
        selectedValues.push(k);
      });
      // v.value_texts
      var selectedNames = [];
      angular.forEach(v.value_texts, function(k){
        selectedNames.push(k);
      });

      $scope.tematics.push({
        title: v.title,
        value: v.slug,
        listToggled: false,
        selectedValues: selectedValues,
        selectedNames: selectedNames
      });

    });

    $scope.editing_data_loaded = true;
  }

  /*$scope.loadTematicItems = function(index, termkey){
    console.log(index+" "+termkey);
  }*/

  $scope.removeParamList = function(index){
    $scope.tematics.splice(index, 1);
  }

  $scope.tematicsValueset = function(index, id, name) {
    var arr = $scope.tematics[index].selectedValues;
    var arrName = $scope.tematics[index].selectedNames;

    if (arr.indexOf(id) == -1) {
      arr.push(id);
      arrName.push(name);
    } else {
      var dix = arr.indexOf(id);
      arr.splice(dix, 1);
      arrName.splice(dix, 1);
    }
  }

  $scope.newTematicListParameter = function(){
    $scope.tematics.push({
      title: null,
      value: null,
      listToggled: false,
      selectedValues: [],
      selectedNames: []
    });
  }

  $scope.tglList = function(l)
  {
    angular.forEach($scope.fromgroup, function(v, k){
      angular.forEach(v, function(v2, k2){
        if(  v2 != l) {
          $scope.listtgl[v2] = false;
        }
      });
    });

    if ($scope.listtgl[l]) {
      $scope.listtgl[l] = false;
    } else {
      $scope.listtgl[l] = true;
    }
  }

  $scope.selectListValue = function(key, id, text, multi) {
    if (multi == false || typeof multi == 'undefined') {
      $scope.selectedlist[key] = {
        id: id,
        text: text
      };
      $scope.allas[key] = id;
      $scope.listtgl[key] = false;
    } else {
      if (typeof $scope.selectedlist[key] === 'undefined') {
        $scope.selectedlist[key] = {};
        $scope.selectedlist[key].ids = [];
        $scope.selectedlist[key].texts = [];
      }

      if ($scope.selectedlist[key].ids.indexOf(id) == -1) {
        $scope.selectedlist[key].ids.push(id);
        $scope.selectedlist[key].texts.push(text);
      }else{
        var ix = $scope.selectedlist[key].ids.indexOf(id);
        $scope.selectedlist[key].ids.splice(ix, 1);
        $scope.selectedlist[key].texts.splice(ix, 1);
      }
    }
  }

  $scope.create = function(){
    $scope.creator_in_progress = true;
    $scope.allas.tematic_list = $scope.tematics;
    $scope.allas.munkakorok = $scope.selectedlist.munkakorok;

    console.log($scope.allas);

    // Adatok mentése, látrehozása
    /* */
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'adscreator',
        data: $scope.allas,
        id: $scope.settings.edit_ad_id,
        admin: $scope.settings.admin,
        by: ($scope.settings.admin) ? $scope.allas.author_id : 'me'
      }
    }).then(function successCallback(response) {
      var d = response.data;
      $scope.creator_in_progress = false;

      console.log(d);

      if(d.success) {
        if ($scope.settings.edit_ad_id == 0) {
          $scope.creator_created = true;
          if(d.creating && d.created_item){
            $timeout(function(){
              if ($scope.settings.admin) {
                document.location.href='/cp/ads/editor/'+d.created_item+'?justcreated=1';
              } else {
                document.location.href='/ugyfelkapu/hirdetesek/mod/'+d.created_item+'?justcreated=1';
              }
            }, 10000);
          }
        } else {
          $scope.creator_saved = true;
        }
      } else {
        $scope.creator_error_msg = d.msg;
      }

    }, function errorCallback(response) {});
    /* */
  }
}]);

ads.controller("Listing", ['$scope', '$http', function($scope, $http){
  $scope.allasok = {};
  $scope.allas_db = 0;
  $scope.loaded = false;
  $scope.error = false;

  $scope.init = function(){
    $scope.loadData();
  }

  $scope.loadData = function(){
    // Lista letöltése
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'adslist',
        author: 'me'
      }
    }).then(function successCallback(response) {
      var d = response.data;
      $scope.loaded = true;

      if (d.success) {
        $scope.allas_db = d.data.length;
        $scope.allasok = d.data;
      } else {
        $scope.error = d.msg;
      }
    }, function errorCallback(response) {});
  }
}]);
