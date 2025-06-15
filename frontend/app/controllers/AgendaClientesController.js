angular.module('meuApp')
    .controller('AgendaClientesController', function ($scope, $http, $state) {
        console.log('AgendaClientesController funcionou!');

        // Criando o objeto agendamentos
        $scope.agendamentos = [];

        // Função que lista os agendamentos
        $scope.agendamentoListar = function () {
            $urlAgendamentoListar = 'http://localhost:8000/api/agendamentos/agendamentoListarH';

            $http.get($urlAgendamentoListar).then(function (response) {
                if (response.status == 200) {
                    console.log('Lista de Agendamentos: ');

                }

            }, function (error) {


            })
        }

    })