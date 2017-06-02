var pm = angular.module("profilModifier", [], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});
pm.controller("formValidor",['$scope', '$http',  function($scope, $http) {
  $scope.default = {};
  $scope.form = {};
  $scope.terms = {};
  $scope.selectedlist = {};
  $scope.listtgl = {};
  $scope.step = '';
  $scope.dataloaded = false;
  $scope.cansavenow = true;
  $scope.texthintfocusstyle = {top: '42px', opacity: 1, filter: 'alpha(opacity=100)'};

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
  };

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
    //console.log(d);
  }, function errorCallback(response) {});

  $scope.save = function(next){
    console.log($scope.form);
  }
}]);
