angular.module('main',[]).controller('mainCtrl', function ($rootScope, $scope, $http) {

    $rootScope.makeAjax = function (myValue, double) {
        if(double){
        delete $rootScope.vid[myValue];
            $rootScope.goo=true;
        }
        if($rootScope.vid[myValue]){
            $rootScope.type = myValue;
        } else {
            $rootScope.goo=true;
            $rootScope.type = myValue;
            $http({
                method: 'GET',
                url: 'parser.php',
                params: {type: myValue},
                timeout : 20000
            }).then(function(responce){
                console.log(responce.data);
                $rootScope.vid[myValue] = responce.data.vid;
                if(!$rootScope.vid[myValue][0]){
                    console.log('inside');
                    $rootScope.makeAjax(myValue, true)
                } else {
                    $rootScope.goo=false;
                }

            }, function(){
                $rootScope.makeAjax(myValue)
            });

        }

    };
    if(!$rootScope.vid){
        $rootScope.vid = {};
        $rootScope.makeAjax('alltime')
    }



});
