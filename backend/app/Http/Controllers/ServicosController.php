<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Servicos;

class ServicosController extends Controller
{
    // Cadastrar novos servicos
    public function procedimentoCadastrar(Request $request)
    {
        // Criar servico
        $servico = new Servicos();
        $servico->tipo_procedimento = $request->tipo_procedimento;
        $servico->duracao_procedimento = $request->duracao_procedimento;
        $servico->preco_procedimento = $request->preco_procedimento;
        $servico->descricao_procedimento = $request->descricao_procedimento;
        $servico->status_procedimento = $request->status_procedimento;
        $servico->created_by = auth()->id();

        // Salvando no BD
        $servico->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'servico cadastrado!',
            'servico' => $servico
        ], 201);
    }

    // Consultar servicos
    public function procedimentoConsultar($id)
    {
        // Selecionando servicos que o usuário administrador criou
        $servico = Servicos::select('id', 'tipo_procedimento', 'duracao_procedimento', 'preco_procedimento', 'descricao_procedimento', 'status_procedimento')->where('id', $id)->where('created_by', auth()->id())->first();

        // Verificando se o servico é null
        if (!$servico)
            return response()->json(['Erro' => 'servico não encontrado!'], 404);

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'servico cadastrado!',
            'servico' => $servico
        ], 200);
    }

    // Listar servicos
    public function procedimentoListar()
    {
        // selecionando as colunas que serão exibidas
        $servico = Servicos::select('id', 'tipo_procedimento', 'duracao_procedimento', 'preco_procedimento', 'descricao_procedimento', 'status_procedimento')->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Lista de Servicos cadastrados: ',
            'servicos: ' => $servico
        ], 200);
    }

    // Editar servico
    public function procedimentoEditar(Request $request, $id)
    {
        // Buscando o servico pelo id
        $servico = Servicos::where('id', $id)->first();

        // Checando se o campo está sendo solicitado para ediçaõ
        if (isset($request->tipo_procedimento))
            $servico->tipo_procedimento = $request->tipo_procedimento;

        if (isset($request->duracao_procedimento))
            $servico->duracao_procedimento = $request->duracao_procedimento;

        if (isset($request->preco_procedimento))
            $servico->preco_procedimento = $request->preco_procedimento;

        if (isset($request->descricao_procedimento))
            $servico->descricao_procedimento = $request->descricao_procedimento;

        if (isset($request->observacao_procedimento))
            $servico->observacao_procedimento = $request->observacao_procedimento;

        if (isset($request->status_procedimento))
            $servico->status_procedimento = $request->status_procedimento;

        $servico->updated_by = auth()->id();
        $servico->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'servico editado: ',
            'servico' => $servico
        ], 200);
    }

    // Filtrar servicos
    public function procedimentoFiltrar(Request $request)
    {
        // Inicia a query com uma condição sempre verdadeira para permitir adicionar filtros dinamicamente
        $servico = Servicos::whereRaw('1=1');

        // Se informado, filtra apenas os servicos criados pelo ID especificado
        if (isset($request->created_by))
            $servico->where('created_by', $request->created_by);

        /* Se informado, busca servicos cujo nome,e-mail, nascimento e celular contenha o valor informado (filtro parcial) */
        if (isset($request->tipo_procedimento))
            $servico->where('tipo_procedimento', 'like', "%$request->tipo_procedimento%");

        if (isset($request->duracao_procedimento))
            $servico->where('duracao_procedimento', 'like', "%$request->duracao_procedimento%");

        if (isset($request->preco_procedimento))
            $servico->where('preco_procedimento', 'like', "%$request->preco_procedimento%");

        if (isset($request->status_procedimento))
            $servico->where('status_procedimento', 'like', "%$request->status_procedimento%");

        $servico = $servico->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Dados filtrados: ',
            'servico' => $servico
        ], 200);
    }

    // Deletar servico
    public function procedimentoDeletar($id)
    {
        // Buscando o servico pelo id
        $servico = Servicos::where('id', $id)->first();

        // Checando se o servico é nulo
        if ($servico == null)
            return response('servico não existe!', 404);

        // Pegando o id de quem deletou e salvando no bd
        $servico->deleted_by = auth()->id();
        $servico->save();

        $servico->delete();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'servico deletado.'
        ], 200);
    }
}
