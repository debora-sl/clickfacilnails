angular.module('meuApp')
    .controller('LoginController', function ($scope, $http, $state) {
        console.log('LoginController funcionou!');

        // Variavel usuario
        $scope.user = {
            email: '',
            password: ''
        }

        // Função para o usuário logar
        $scope.userLogar = function () {
            console.log($scope.user);
            $urlUserLogin = 'http://localhost:8000/api/userLogin';

            $http.post($urlUserLogin, $scope.user).then(function (response) {
                console.log('Dados do login ok!', response);

                // Setando o Token do usuário
                localStorage.setItem('token', response.data.token);

                // Enviando o usuário para a página de home de usuários logados
                $state.go('agendaAdmProprietaria');

            }, function (error) {
                console.log('Erro', error);
                // Alerta para caso os dados de e-mail ou senha estejam incorretos
                Swal.fire({
                    title: 'Error!',
                    text: 'E-mail ou Senha invalidos',
                    icon: 'error'
                });
            })

        }

    })
