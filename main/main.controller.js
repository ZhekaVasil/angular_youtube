angular.module('main',[]).controller('mainCtrl', function ($rootScope, $scope, $http, $location) {
    var test = true;
    $rootScope.makeAjax = function (myValue, double, firstime, problem) {
        if(!myValue) {
            var url = $location.absUrl();
            try {
                myValue = url.match(/[a-z]+$/g)[0];
            } catch (e) {
                myValue = 'alltime';
            }
        }
        if(!problem){
            $rootScope.type = myValue;
        }
        if(double){
            delete $rootScope.vid[myValue];
            $http({
                method: 'GET',
                url: 'parser.php',
                params: {type: myValue},
                timeout: 20000
            }).then(function (responce) {
                console.log('double');
                $rootScope.vid[myValue] = responce.data.vid;
                if (!$rootScope.vid[myValue][0] || $rootScope.vid[myValue][0].ico == '') {
                    console.log('inside double');
                    $rootScope.makeAjax(myValue, true)
                } else {
                    $rootScope.goo = false;
                }
            }, function () {
                $rootScope.makeAjax(myValue)
            });
        } else {
            if(!firstime) return;
            $rootScope.goo=true;
            $http({
                method: 'GET',
                url: 'json/parser.json',
                timeout : 20000
            }).then(function(responce){
                console.log('first');
                $rootScope.vid = responce.data.vid;
                for(var key in $rootScope.vid){
                    if($rootScope.vid[key].length==0 || $rootScope.vid[key][0].ico==''){
                        test = false;
                        $rootScope.makeAjax(key, true, false, true);
                    }
                }
                if(test) $rootScope.goo=false;


            }, function(){
                $rootScope.makeAjax(myValue)
            });
        }
    };



    if(!$rootScope.vid){
        if($location.url().indexOf('item') == -1) {
            $rootScope.vid = {};
            $rootScope.makeAjax(false, false, true)
        }

    }



});
