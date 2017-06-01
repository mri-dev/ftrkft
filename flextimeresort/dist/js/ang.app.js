var pm = angular.module("profilModifier", []);
pm.controller("formValidor",['$scope', '$http', '$log',  function($scope, $http, $log) {
  $scope.default = {};
  $scope.dataloaded = false;
  $scope.cansavenow = true;

  $scope.validateUserData = function(user){
    // Pass
    $scope.default.name = user.alap.name;
    $scope.default.email = user.alap.email;
  };

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

    }, function errorCallback(response) {
      // called asynchronously if an error occurs
      // or server returns response with an error status.
    });
}]);
