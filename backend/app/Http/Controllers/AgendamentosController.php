<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agendamentos;
use App\Models\User;

class AgendamentosController extends Controller
{
    // Cadastrar novos agendamentos
    public function agendamentoCadastrar(Request $request)
    {
        // Criar agendamento
        $agendamento = new Agendamentos();
        $agendamento->id_usuario = $request->id_usuario;
        $agendamento->id_procedimento = $request->id_procedimento;
        $agendamento->data_agendamento = $request->data_agendamento;
        $agendamento->horario_agendamento = $request->horario_agendamento;
        $agendamento->created_by = auth()->id();

        // Setando para que não seja null para não dar erro ao cadastrar
        if (isset($request->observacao_agendamento))
            $agendamento->observacao_agendamento = $request->observacao_agendamento;
        if (isset($request->status_agendamento))
            $agendamento->status_agendamento = $request->status_agendamento;

        // Salvando no BD
        $agendamento->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Agendamento cadastrado!',
            'agendamento' => $agendamento
        ], 201);
    }

    // Consultar agendamentos
    public function agendamentoConsultar($id)
    {
        // Selecionando agendamentos que o usuário administrador criou
        $agendamento = Agendamentos::select('id', 'id_usuario', 'id_procedimento', 'data_agendamento', 'horario_agendamento', 'observacao_agendamento', 'status_agendamento')->where('id', $id)->where('created_by', auth()->id())->first();

        // Verificando se o agendamento é null
        if (!$agendamento)
            return response()->json(['Erro' => 'Agendamento não encontrado!'], 404);

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Agendamento',
            'agendamento' => $agendamento
        ], 200);
    }

    // Listar agendamentos
    public function agendamentoListar()
    {
        // selecionando as colunas que serão exibidas
        $agendamento = Agendamentos::select('id', 'id_usuario', 'id_procedimento', 'data_agendamento', 'horario_agendamento', 'observacao_agendamento', 'status_agendamento')->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Lista de Agendamentos cadastrados: ',
            'agendamentos: ' => $agendamento
        ], 200);
    }

    // Editar agendamento
    public function agendamentoEditar(Request $request, $id)
    {
        // Buscando o agendamento pelo id
        $agendamento = Agendamentos::where('id', $id)->first();

        // Checando se o campo está sendo solicitado para ediçaõ
        if (isset($request->id_agendamento))
            $agendamento->id_agendamento = $request->id_agendamento;

        if (isset($request->data_agendamento))
            $agendamento->data_agendamento = $request->data_agendamento;

        if (isset($request->horario_agendamento))
            $agendamento->horario_agendamento = $request->horario_agendamento;

        if (isset($request->observacao_agendamento))
            $agendamento->observacao_agendamento = $request->observacao_agendamento;

        if (isset($request->status_agendamento))
            $agendamento->status_agendamento = $request->status_agendamento;

        $agendamento->updated_by = auth()->id();
        $agendamento->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Agendamento editado: ',
            'agendamento' => $agendamento
        ], 200);
    }

    // Filtrar agendamentos
    public function agendamentoFiltrar(Request $request)
    {
        // Inicia a query com uma condição sempre verdadeira para permitir adicionar filtros dinamicamente
        $agendamento = Agendamentos::whereRaw('1=1');

        // Se informado, filtra apenas os agendamentos criados pelo ID especificado
        if (isset($request->created_by))
            $agendamento->where('created_by', $request->created_by);

        /* Se informado, busca agendamentos cujo data,e-mail contenha o valor informado (filtro parcial) */
        if (isset($request->id_usuario))
            $agendamento->where('id_usuario', 'like', "%$request->id_usuario%");
        if (isset($request->id_procedimento))
            $agendamento->where('id_procedimento', 'like', "%$request->id_procedimento%");
        if (isset($request->data_agendamento))
            $agendamento->where('data_agendamento', 'like', "%$request->data_agendamento%");
        if (isset($request->horario_agendamento))
            $agendamento->where('horario_agendamento', 'like', "%$request->horario_agendamento%");
        if (isset($request->status_agendamento))
            $agendamento->where('status_agendamento', 'like', "%$request->status_agendamento%");

        $agendamento = $agendamento->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Dados filtrados: ',
            'agendamento' => $agendamento
        ], 200);
    }

    // Deletar agendamento
    public function agendamentoDeletar($id)
    {
        // Buscando o agendamento pelo id
        $agendamento = Agendamentos::where('id', $id)->first();

        // Checando se o agendamento é nulo
        if ($agendamento == null)
            return response('Agendamento não existe!', 404);

        // Pegando o id de quem deletou e salvando no bd
        $agendamento->deleted_by = auth()->id();
        $agendamento->save();

        $agendamento->delete();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'agendamento deletado.'
        ], 200);
    }
}
