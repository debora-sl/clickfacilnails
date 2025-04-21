<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perfis;

class PerfisController extends Controller
{
    // Cadastrar novos perfis
    public function perfilCadastrar(Request $request)
    {
        // Criar perfil
        $perfil = new Perfis();
        $perfil->created_by = auth()->id();

        // Setando para que não seja null para não dar erro ao cadastrar
        if (isset($request->nome_perfil))
            $perfil->nome_perfil = $request->nome_perfil;
        if (isset($request->tela_inicial_perfil))
            $perfil->tela_inicial_perfil = $request->tela_inicial_perfil;

        // Salvando no BD
        $perfil->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Perfil cadastrado!',
            'perfil' => $perfil
        ], 201);
    }

    // Consultar perfis
    public function perfilConsultar($id)
    {
        // Selecionando perfis que o usuário administrador criou
        $perfil = Perfis::select('id', 'nome_perfil')->where('id', $id)->where('created_by', auth()->id())->first();

        // Verificando se o perfil é null
        if (!$perfil)
            return response()->json(['Erro' => 'Perfil não encontrado!'], 404);

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Perfil',
            'perfil' => $perfil
        ], 200);
    }

    // Listar perfis
    public function perfilListar()
    {
        // selecionando as colunas que serão exibidas
        $perfil = Perfis::select('id', 'nome_perfil')->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Lista de Perfis cadastrados: ',
            'perfis: ' => $perfil
        ], 200);
    }

    // Editar perfil
    public function perfilEditar(Request $request, $id)
    {
        // Buscando o perfil pelo id
        $perfil = Perfis::where('id', $id)->first();

        // Verificando se o perfil existe
        if (!$perfil) {
            return response()->json(['mensagem' => 'Perfil não encontrado.'], 404);
        }

        // Checando se o campo está sendo solicitado para ediçaõ
        if (isset($request->nome_perfil)) {
            $perfil->nome_perfil = $request->nome_perfil;

            // Atualizando automaticamente o valor de tela_inicial_perfil
            if ($request->nome_perfil === 'Adm Proprietaria') {
                $perfil->tela_inicial_perfil = 'Agendamento geral';
            } elseif ($request->nome_perfil === 'Cliente') {
                $perfil->tela_inicial_perfil = 'Agendamento próprio';
            }
        }

        // Salnado o id do usuario que editou
        $perfil->updated_by = auth()->id();
        $perfil->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Perfil editado: ',
            'perfil' => $perfil
        ], 200);
    }

    // Filtrar perfis
    public function perfilFiltrar(Request $request)
    {
        // Inicia a query com uma condição sempre verdadeira para permitir adicionar filtros dinamicamente
        $perfil = Perfis::whereRaw('1=1');

        // Se informado, filtra apenas os perfis criados pelo ID especificado
        if (isset($request->created_by))
            $perfil->where('created_by', $request->created_by);

        /* Se informado, busca perfis cujo o nome do perfil contenha o valor informado (filtro parcial) */
        if (isset($request->nome_perfil))
            $perfil->where('nome_perfil', 'like', "%$request->nome_perfil%");

        $perfil = $perfil->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Dados filtrados: ',
            'perfil' => $perfil
        ], 200);
    }

    // Deletar perfil
    public function perfilDeletar($id)
    {
        // Buscando o perfil pelo id
        $perfil = Perfis::where('id', $id)->first();

        // Checando se o perfil é nulo
        if ($perfil == null)
            return response('Perfil não existe!', 404);

        // Pegando o id de quem deletou e salvando no bd
        $perfil->deleted_by = auth()->id();
        $perfil->save();

        $perfil->delete();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Perfil deletado.'
        ], 200);
    }
}
