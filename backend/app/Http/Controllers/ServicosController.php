<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Servicos;

class ServicosController extends Controller
{
    // Cadastrar novos servicos
    public function servicoCadastrar(Request $request)
    {
        // Criar servico
        $servico = new Servicos();
        $servico->tipo_servico = $request->tipo_servico;
        $servico->duracao_servico = $request->duracao_servico;
        $servico->preco_servico = $request->preco_servico;
        $servico->descricao_servico = $request->descricao_servico;
        $servico->status_servico = $request->status_servico;
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
    public function servicoConsultar($id)
    {
        // Selecionando servicos que o usuário administrador criou
        $servico = Servicos::select('id', 'tipo_servico', 'duracao_servico', 'preco_servico', 'descricao_servico', 'status_servico')->where('id', $id)->where('created_by', auth()->id())->first();

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
    public function servicoListar()
    {
        // selecionando as colunas que serão exibidas
        $servico = Servicos::select('id', 'tipo_servico', 'duracao_servico', 'preco_servico', 'descricao_servico', 'status_servico')->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Lista de Servicos cadastrados: ',
            'servicos: ' => $servico
        ], 200);
    }

    // Editar servico
    public function servicoEditar(Request $request, $id)
    {
        // Buscando o servico pelo id
        $servico = Servicos::where('id', $id)->first();

        // Checando se o campo está sendo solicitado para ediçaõ
        if (isset($request->tipo_servico))
            $servico->tipo_servico = $request->tipo_servico;

        if (isset($request->duracao_servico))
            $servico->duracao_servico = $request->duracao_servico;

        if (isset($request->preco_servico))
            $servico->preco_servico = $request->preco_servico;

        if (isset($request->descricao_servico))
            $servico->descricao_servico = $request->descricao_servico;

        if (isset($request->observacao_servico))
            $servico->observacao_servico = $request->observacao_servico;

        if (isset($request->status_servico))
            $servico->status_servico = $request->status_servico;

        $servico->updated_by = auth()->id();
        $servico->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'servico editado: ',
            'servico' => $servico
        ], 200);
    }

    // Filtrar servicos
    public function servicoFiltrar(Request $request)
    {
        // Inicia a query com uma condição sempre verdadeira para permitir adicionar filtros dinamicamente
        $servico = Servicos::whereRaw('1=1');

        // Se informado, filtra apenas os servicos criados pelo ID especificado
        if (isset($request->created_by))
            $servico->where('created_by', $request->created_by);

        /* Se informado, busca servicos cujo nome,e-mail, nascimento e celular contenha o valor informado (filtro parcial) */
        if (isset($request->tipo_servico))
            $servico->where('tipo_servico', 'like', "%$request->tipo_servico%");

        if (isset($request->duracao_servico))
            $servico->where('duracao_servico', 'like', "%$request->duracao_servico%");

        if (isset($request->preco_servico))
            $servico->where('preco_servico', 'like', "%$request->preco_servico%");

        if (isset($request->status_servico))
            $servico->where('status_servico', 'like', "%$request->status_servico%");

        $servico = $servico->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Dados filtrados: ',
            'servico' => $servico
        ], 200);
    }

    // Deletar servico
    public function servicoDeletar($id)
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
