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
            .state('login', {
                url: '/login',
                templateUrl: 'app/views/paginas/login.html',
                controller: 'LoginController'
            })
            .state('logout', {
                url: '/logout',
                templateUrl: 'app/views/paginas/logout.html',
                controller: 'LogoutController'
            })
            .state('userCadastrar', {
                url: '/userCadastrar',
                templateUrl: 'app/views/paginas/userCadastrar.html',
                controller: 'UserCadastrarController'
            })
            .state('agendaAdmProprietaria', {
                url: '/agendaAdmProprietaria',
                templateUrl: 'app/views/paginas/agendaAdmProprietaria.html',
                controller: 'AgendaAdmProprietariaController'
            })
            .state('agendaClientes', {
                url: '/agendaClientes',
                templateUrl: 'app/views/paginas/agendaClientes.html',
                controller: 'AgendaClientesController'
            })
        $urlRouterProvider.otherwise('/');
    });