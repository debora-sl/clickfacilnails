angular.module('meuApp')
    .controller('UserCadastrarController', function ($scope, $http, $state) {
        console.log('UserCadastrarController funcionou!');

        // Variavel novo usuario
        $scope.dadosUserNovo = {
            name: '',
            email: '',
            password: '',
            passwordConfirme: '',
            nascimento: '',
            celular: '',
        }

        // Função que limpa o formulário para cadastrar novos usuário
        $scope.limparFormulario = function () {
            $scope.dadosUserNovo = {
                name: '',
                email: '',
                password: '',
                passwordConfirme: '',
                nascimento: '',
                celular: '',
            }
        }

        // Cadastrar novo usuários
        $scope.userCadastrar = function () {
            $urlUserCadastrar = 'http://localhost:8000/api/users/userCadastrar';

            // Verificando se a senha e senha confirme estão iguais
            if ($scope.dadosUserNovo.password != $scope.dadosUserNovo.passwordConfirme) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "As senhas são diferentes!",
                });
            } else {
                // Enviando os dados para API e BD
                $http.post($urlUserCadastrar, $scope.dadosUserNovo).then(function (response) {
                    console.log('Usuário cadastrado', $scope.dadosUserNovo);
                    // Verificadando se o usuário foi criado e o enviando para o login
                    if (response.status == 201) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Usuário cadastrado com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(function () {
                            $state.go("login");
                        });
                    }
                }, function (error) {
                    console.log('Erro, usuário não cadastrado', error);
                    // Se não foi cadastrado, avisando para o usuário verificar
                    Swal.fire({
                        icon: "error",
                        title: "Verifique os dados!",
                        icon: 'error',
                        confirmButtonText: 'Voltar'
                    });

                })
            }

        }
    })
