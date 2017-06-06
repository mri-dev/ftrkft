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
