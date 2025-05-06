angular.module('meuApp')
    .controller('HomeController', function ($scope, $http, $state) {
        console.log('HomeController funcionou!');

        // Criando o objeto servicos
        $scope.servicos = [];

        // Função que lista os servicos
        $scope.servicoListar = function () {
            $urlServicoListar = 'http://localhost:8000/api/servicos/servicoListar';

            $http.get($urlServicoListar).then(function (response) {
                if (response.status == 200) {
                    $scope.servicos = response.data.servicos;
                    console.log('Servicos: ', $scope.servicos);
                }

            }, function (error) {
                console.log('Erro para listar: ', error);

            })
        }

        // Chamando a função listar
        $scope.servicoListar();


        // Variavel dia da semana
        $scope.diaDaSemana = tratarDiasSemana();

        // Função para os dias da semana
        function tratarDiasSemana() {
            const diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            let dias = [];

            // Pega a data de hoje
            const hoje = new Date();
            const diaDaSemana = hoje.getDay(); // 0 = domingo, 1 = segunda...

            // Calcula quantos dias voltar para chegar na segunda-feira da semana atual
            const diferencaParaSegunda = diaDaSemana === 0 ? -6 : 1 - diaDaSemana;
            const segunda = new Date(hoje);
            segunda.setDate(hoje.getDate() + diferencaParaSegunda);

            // Gera dias de segunda a sábado (6 dias)
            for (let i = 0; i < 6; i++) {
                let data = new Date(segunda);
                data.setDate(segunda.getDate() + i);

                let nomeDia = diasSemana[data.getDay()];
                let dia = data.getDate().toString().padStart(2, '0');
                let mes = (data.getMonth() + 1).toString().padStart(2, '0');
                let ano = data.getFullYear();

                let dataFormatada = `${dia}/${mes}`;
                let dataCompleta = `${ano}-${mes}-${dia}`; // Formato igual ao do banco

                dias.push({
                    nomeDia: nomeDia,
                    dataFormatada: dataFormatada,
                    dataCompleta: dataCompleta
                });
            }

            return dias;
        }

        // Criando o objeto agendamentos
        $scope.agendamentos = [];

        // Função que lista os agendamentos
        $scope.agendamentoListarHome = function () {
            $urlAgendamentoListar = 'http://localhost:8000/api/agendamentos/agendamentoListarHome';

            $http.get($urlAgendamentoListar).then(function (response) {
                if (response.status == 200) {
                    $scope.agendamentos = tratarHorarios(response.data.agendamentos);
                    console.log('Agendamentos', $scope.agendamentos);
                }

            }, function (error) {
                console.log('Erro', error);

            })
        }

        // Função que formata os horários para padrão brasileiro (hh:mm)
        function tratarHorarios(dados) {
            console.log(dados);

            for (let i = 0; i < dados.length; i++) {
                // Converte a string em objeto Date
                let horaOriginal = new Date('1970-01-01T' + dados[i].horario_agendamento);

                // Formata a hora como hh:mm
                let horas = horaOriginal.getHours().toString().padStart(2, '0');
                let minutos = horaOriginal.getMinutes().toString().padStart(2, '0');

                // Salva o horário formatado em uma nova propriedade (pra não perder o original)
                dados[i].horarioFormatado = `${horas}:${minutos}`;
            }

            return dados;
        }

        // Chamando a função listar
        $scope.agendamentoListarHome();
    })