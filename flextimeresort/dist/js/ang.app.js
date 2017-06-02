var pm = angular.module("profilModifier", [], function($interpolateProvider){
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
});
pm.controller("formValidor",['$scope', '$http',  function($scope, $http) {
  $scope.default = {};
  $scope.form = {};
  $scope.terms = {};
  $scope.selectedlist = {};
  $scope.step = '';
  $scope.dataloaded = false;
  $scope.cansavenow = true;

  $scope.fromgroup = {
    alap: ['name', 'email', 'nem']
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
  }

  // Lista letöltése
  $http({
    method: 'POST',
    url: '/ajax/data',
    params: {
      type: 'lists',
      lists: 'nem,megyek'
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
    
  }
}]);
