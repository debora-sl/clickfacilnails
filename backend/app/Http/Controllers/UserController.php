<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Cadastrar novos usuários (usuários não autenticados)
    public function userCadastrar(Request $request)
    {
        // Checando se o e-mail já existe
        $userQTD = User::where('email', $request->email)->count();

        if ($userQTD > 0)
            return response('Conflito', 409);

        // Criando o usuário
        $user = new User();
        $user->id_perfil = $request->id_perfil;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->nascimento = $request->nascimento;
        $user->celular = $request->celular;
        $user->created_by = 0;
        $user->save();

        return response('Usuário cadastrado: ' . $user, 201);
    }

    // Logar usuários
    public function userLogar(Request $request)
    {
        // verificando se o usuario é valido
        if (isset($request->email) && isset($request->password)) {
            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);


            if ($token = JWTAuth::attempt($validate)) {
                return response()->json(['token' => $token], 200);
            }
        }

        return response('Usuário invalido!', 401);
    }

    // Consultar
    public function userConsultar($id)
    {

        /* busca um único registro na tabela users, onde a coluna id é igual ao valor de $id, equivalente à: $user = User::where('id', $id)->first();*/
        $user = User::find($id);

        // checando se usuário existe
        if (!$user)
            return response()->json(['erro' => 'Usuário não encontrado!'], 404);

        // makeHidden(['dado']) é omitido na exibição, "faça oculto"
        $user->makeHidden(['password']);

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Usuário consultado!',
            'user' => $user
        ], 200);
    }

    // Listar
    public function userListar()
    {
        // selecionando as colunas que serão exibidas
        $user = User::select('id', 'id_perfil', 'name', 'email', 'celular', 'nascimento')->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Usuários cadastrados: ',
            'user' => $user
        ], 200);
    }

    // Editar
    public function userEditar(Request $request, $id)
    {
        // Buscando o usuário pelo id
        $user = User::where('id', $id)->first();

        // Checando se o campo está sendo solicitado para alteração
        if (isset($request->name))
            $user->name = $request->name;

        if (isset($request->email))
            $user->email = $request->email;

        if (isset($request->nascimento))
            $user->nascimento = $request->nascimento;

        if (isset($request->celular))
            $user->celular = $request->celular;

        // Verificando se o campo de senha está vazio
        if (isset($request->password) && $request->password != '')
            $user->password = bcrypt($request->password);

        $user->updated_by = auth()->id();
        $user->save();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Usuário Editado: ',
            'user' => $user
        ], 200);
    }

    // Filtrar
    public function userFiltrar(Request $request)
    {
        // Inicia a query com uma condição sempre verdadeira para permitir adicionar filtros dinamicamente
        $user = User::whereRaw('1=1');

        // Se informado, filtra apenas os usuários criados pelo ID especificado (ex: user logado)
        if (isset($request->created_by))
            $user->where('created_by', $request->created_by);

        /* Se informado, busca usuários cujo nome,e-mail, nascimento e celular contenha o valor informado (filtro parcial) */
        if (isset($request->name))
            $user->where('name', 'like', "%$request->name%");

        if (isset($request->email))
            $user->where('email', 'like', "%$request->email%");

        if (isset($request->nascimento))
            $user->where('nascimento', 'like', "%$request->nascimento%");

        if (isset($request->celular))
            $user->where('celular', 'like', "%$request->celular%");

        $user = $user->get();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Dados filtrados: ',
            'user' => $user
        ], 200);
    }

    // Deletar
    public function userDeletar($id)
    {
        // Buscando o usuário pelo id
        $user = User::where('id', $id)->first();

        // Checando se o user é nulo
        if ($user == null)
            return response('Usuário não existe!', 404);

        // Pegando o id de quem deletou e salvando no bd
        $user->deleted_by = auth()->id();
        $user->save();

        $user->delete();

        // retornando com estrutura JSON para o front consumir
        return response()->json([
            'mensagem' => 'Usuário deletado.'
        ], 200);
    }
}
