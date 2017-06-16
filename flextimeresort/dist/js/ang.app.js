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

  $scope.fromgroup = {
    alap: ['name', 'email', 'nem', 'allampolgarsag', 'csaladi_allapot', 'anyanyelv']
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
    // Pass
    $scope.form.name = user.alap.name;
    $scope.form.email = user.alap.email;
    $scope.form.szuletesi_datum = user.alap.szuletesi_datum;
    $scope.profilpreview = user.alap.profil_kep;

    // List
    var termcicle = 0;
    angular.forEach($scope.terms, function(v, k){
      var ld = $scope.terms[k][user.alap[k]];
      termcicle++;

      if (typeof ld !== 'undefined') {
        $scope.form[k] = user.alap[k];
        $scope.selectedlist[k] = {
          id: ld.id,
          text: ld.value
        };
      }
    });

    if(termcicle == 0) {
      window.location.reload();
    }
  };

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
      lists: 'nem,megyek,allampolgarsag,csaladi_allapot,anyanyelv'
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
    console.log($scope.step);
    console.log($scope.form);
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

      // Felhasználó adatok mentése
      $http({
        method: 'POST',
        url: '/ajax/data',
        params: {
          type: 'profilsave',
          form: $scope.form,
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
 }]);

/**
* Ügyfélkapu üzenetváltó modul
**/
var msg = angular.module("UserMessanger", [], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

msg.controller( "MessagesList", ['$scope', '$http', function($scope, $http)
{
  $scope.is_msg = false;
  $scope.unreaded_messages = {
    inbox: 0,
    outbox: 0
  };
  $scope.messages = {};
  $scope.result = {};
  $scope.newnoticemsg = {};
  $scope.msgtgl = {};
  $scope.newmsg_left_length = 1000;
  $scope.newmsg = null;
  $scope.newmsg_focused = false;
  $scope.newmsg_send_progress = false;
  $scope.newmsgerrmsg=false;

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
    console.log($scope.newmsg);
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

          console.log(d);
        }, function errorCallback(response) {});
      }
    }
  }

  $scope.syncMessages = function(){
    $scope.loadMessages();
  }

  $scope.loadMessages = function(type){
    console.log(type);
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

      console.log(d);
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
* Hirdetés létrehozó
**/
var ads = angular.module("Ads", [], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});

ads.controller( "Creator", ['$scope', '$http', function($scope, $http)
{
  $scope.settings = {};
  $scope.terms = {};
  $scope.listtgl = {};
  $scope.allas = {};
  $scope.selectedlist = {};
  $scope.userdata = {};
  $scope.term_list = {};
  $scope.tematics = [];

  $scope.cansavenow = true;
  $scope.saveinprogress = false;
  $scope.successfullsaved = false;
  $scope.dataloaded = false;

  $scope.short_desc_length = 150;
  $scope.keywords_length = 100;

  $scope.init = function(admin, authorid)
  {
    $scope.settings.admin = (admin == 0) ? false : true;

    $scope.allas.author_id = authorid;
    $scope.allas.created_by_admin = admin;

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
      console.log(response.data);
    }, function errorCallback(response) {});

    // Lista letöltése
    $http({
      method: 'POST',
      url: '/ajax/data',
      params: {
        type: 'lists',
        filters: {
          listforads: 1
        }
      }
    }).then(function successCallback(response) {
      var d = response.data;
      $scope.term_list = d.lists;
      angular.forEach(d.lists, function(v, k){
        $scope.terms[v.termkey] = d.terms[v.termkey];
      });
      $scope.dataloaded = true;
      console.log(d);
    }, function errorCallback(response) {});
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

  $scope.selectListValue = function(key, id, text) {
    $scope.selectedlist[key] = {
      id: id,
      text: text
    };
    $scope.allas[key] = id;
    $scope.listtgl[key] = false;
  }

  $scope.create = function(){
    console.log($scope.allas);
  }
}]);
