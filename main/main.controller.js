angular.module('main',[]).controller('mainCtrl', function ($rootScope, $scope, $http) {

    $scope.makeAjax = function (myValue) {
        if($rootScope.vid[myValue]){
            $rootScope.type = myValue;
        } else {
            $http({
                method: 'GET',
                url: 'parser.php',
                params: {type: myValue},
                timeout : 20000
            }).then(function(responce){
                console.log(responce.data);
                $rootScope.vid[myValue] = responce.data.vid;
                if($rootScope.vid[myValue].length==0 || responce.data.vid.length==0 ){
                    $scope.makeAjax(myValue)
                }
                $rootScope.views = responce.data.views;
                $rootScope.type = myValue;
            }, function(){
                $scope.makeAjax(myValue)
            });
        }

    };

    if(!$rootScope.vid){
        $rootScope.vid = {};
        $scope.makeAjax('alltime')
    }



});
