angular.module('item',[]).controller('itemCtrl', function ($scope, $routeParams) {
    $scope.id = $routeParams.id;
    $scope.src = 'https://www.youtube.com/embed/' + $scope.id;
});
