angular.module('yt').config(['$locationProvider', '$routeProvider', '$sceDelegateProvider',
    function config($locationProvider, $routeProvider, $sceDelegateProvider) {
        $routeProvider.when('/', {
            templateUrl: 'main/main.html'
        }).when('/item/:id',{
            templateUrl: 'item/item.html'
        }).otherwise('main/main.html');
        $locationProvider.html5Mode(false);
        $sceDelegateProvider.resourceUrlWhitelist([
            // Allow same origin resource loads.
            'self',
            // Allow loading from our assets domain.  Notice the difference between * and **.
            'https://www.youtube.com/embed/**']);
    }


]);