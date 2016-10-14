angular.module('yt').config(['$locationProvider', '$routeProvider', '$sceDelegateProvider',
    function config($locationProvider, $routeProvider, $sceDelegateProvider, $routeParams) {
        $routeProvider.when('/', {
            templateUrl: 'alltime/alltime.html'
        }).when('/item/:id',{
            templateUrl: 'item/item.html'
        }).when('/hour',{
            templateUrl: 'hour/hour.html'
        }).when('/today',{
            templateUrl: 'today/today.html'
        }).when('/week',{
            templateUrl: 'week/week.html'
        }).when('/mounth',{
            templateUrl: 'mounth/mounth.html'
        }).when('/year',{
            templateUrl: 'year/year.html'
        }).when('/alltime',{
            templateUrl: 'alltime/alltime.html'
        }).otherwise('alltime/alltime.html');
        $locationProvider.html5Mode(false);
        $sceDelegateProvider.resourceUrlWhitelist([
            // Allow same origin resource loads.
            'self',
            // Allow loading from our assets domain.  Notice the difference between * and **.
            'https://www.youtube.com/embed/**']);
    }


]);