<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Procedimentos;

class ProcedimentosController extends Controller
{
    // Cadastrar novos procedimentos
    public function procedimentoCadastrar(Request $request)
    {
        // Criar procedimento
        $procedimento = new Procedimentos();
        $procedimento->tipo_procedimento = $request->tipo_procedimento;
        $procedimento->duracao_procedimento = $request->duracao_procedimento;
        $procedimento->preco_procedimento = $request->preco_procedimento;
        $procedimento->descricao_procedimento = $request->descricao_procedimento;
        $procedimento->status_procedimento = $request->status_procedimento;
        $procedimento->created_by = auth()->id();

        // Salvando no BD
        $procedimento->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Procedimento cadastrado!',
            'procedimento' => $procedimento
        ], 201);
    }

    // Consultar procedimentos
    public function procedimentoConsultar($id)
    {
        // Selecionando procedimentos que o usuário administrador criou
        $procedimento = Procedimentos::select('id', 'tipo_procedimento', 'duracao_procedimento', 'preco_procedimento', 'descricao_procedimento', 'status_procedimento')->where('id', $id)->where('created_by', auth()->id())->first();

        // Verificando se o procedimento é null
        if (!$procedimento)
            return response()->json(['Erro' => 'Procedimento não encontrado!'], 404);

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Procedimento cadastrado!',
            'procedimento' => $procedimento
        ], 200);
    }

    // Listar procedimentos
    public function procedimentoListar()
    {
        // selecionando as colunas que serão exibidas
        $procedimento = Procedimentos::select('id', 'tipo_procedimento', 'duracao_procedimento', 'preco_procedimento', 'descricao_procedimento', 'status_procedimento')->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Lista de Procedimentos cadastrados: ',
            'procedimentos: ' => $procedimento
        ], 200);
    }

    // Editar procedimento
    public function procedimentoEditar(Request $request, $id)
    {
        // Buscando o procedimento pelo id
        $procedimento = Procedimentos::where('id', $id)->first();

        // Checando se o campo está sendo solicitado para ediçaõ
        if (isset($request->tipo_procedimento))
            $procedimento->tipo_procedimento = $request->tipo_procedimento;

        if (isset($request->duracao_procedimento))
            $procedimento->duracao_procedimento = $request->duracao_procedimento;

        if (isset($request->preco_procedimento))
            $procedimento->preco_procedimento = $request->preco_procedimento;

        if (isset($request->descricao_procedimento))
            $procedimento->descricao_procedimento = $request->descricao_procedimento;

        if (isset($request->observacao_procedimento))
            $procedimento->observacao_procedimento = $request->observacao_procedimento;

        if (isset($request->status_procedimento))
            $procedimento->status_procedimento = $request->status_procedimento;

        $procedimento->updated_by = auth()->id();
        $procedimento->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Procedimento editado: ',
            'procedimento' => $procedimento
        ], 200);
    }

    // Filtrar procedimentos
    public function procedimentoFiltrar(Request $request)
    {
        // Inicia a query com uma condição sempre verdadeira para permitir adicionar filtros dinamicamente
        $procedimento = Procedimentos::whereRaw('1=1');

        // Se informado, filtra apenas os procedimentos criados pelo ID especificado
        if (isset($request->created_by))
            $procedimento->where('created_by', $request->created_by);

        /* Se informado, busca procedimentos cujo nome,e-mail, nascimento e celular contenha o valor informado (filtro parcial) */
        if (isset($request->tipo_procedimento))
            $procedimento->where('tipo_procedimento', 'like', "%$request->tipo_procedimento%");

        if (isset($request->duracao_procedimento))
            $procedimento->where('duracao_procedimento', 'like', "%$request->duracao_procedimento%");

        if (isset($request->preco_procedimento))
            $procedimento->where('preco_procedimento', 'like', "%$request->preco_procedimento%");

        if (isset($request->status_procedimento))
            $procedimento->where('status_procedimento', 'like', "%$request->status_procedimento%");

        $procedimento = $procedimento->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Dados filtrados: ',
            'procedimento' => $procedimento
        ], 200);
    }

    // Deletar procedimento
    public function procedimentoDeletar($id)
    {
        // Buscando o procedimento pelo id
        $procedimento = Procedimentos::where('id', $id)->first();

        // Checando se o procedimento é nulo
        if ($procedimento == null)
            return response('Procedimento não existe!', 404);

        // Pegando o id de quem deletou e salvando no bd
        $procedimento->deleted_by = auth()->id();
        $procedimento->save();

        $procedimento->delete();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Procedimento deletado.'
        ], 200);
    }
}
