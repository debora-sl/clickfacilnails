angular.module('meuApp')
    .controller('LogoutController', function ($scope, $http, $state) {
        console.log('LogoutController funcionou!');

        // Pegando o token do usuário logado
        $token = localStorage.getItem('token');

        $configuracaoCabecalhoAutenticado = {
            headers: {
                'Authorization': 'Bearer ' + $token
            }
        }

        // Enviando o usuário para API para o Logout
        $urlUserLogout = 'http://localhost:8000/api/logout';

        $http.get($urlUserLogout, $configuracaoCabecalhoAutenticado).then(function (response) {
            console.log('Logout funcionou', response);

            // Removendo o Token do usuário do locaçStorage
            localStorage.removeItem('token');

            // Direcionado o usuário para o Home
            $state.go('home');

        }, function (error) {
            // Removendo o Token do usuário do locaçStorage
            localStorage.removeItem('token');

            // Direcionado o usuário para o Home
            $state.go('home');
        })

    })