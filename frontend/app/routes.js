angular.module('meuApp', ['ui.router'])
    .config(function ($stateProvider, $urlRouterProvider) {
        $stateProvider
            .state('comMenu', {
                abstract: true,
                templateUrl: 'app/views/agenda.html',
                controller: ''
            })
            .state('home', {
                url: '/',
                templateUrl: 'app/views/home.html',
                controller: 'HomeController'
            })
        $urlRouterProvider.otherwise('/');
    });