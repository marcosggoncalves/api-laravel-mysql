<?php

namespace App\Http\Controllers;

use App\Interfaces\IUsuariosInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class UsuariosController extends Controller
{
    protected $repository;

    public function __construct(IUsuariosInterface $repository)
    {
        $this->middleware(['auth'], ['except' => ['login']]);

        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/usuarios",
     *      operationId="listar_usuarios",
     *      tags={"Usuarios"},
     *      summary="Listar usuarios",
     *      description="Retornar todos os cadastros de usuarios",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *       @OA\Schema(
     *           type="number"
     *       )
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Tudo certo!",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado, login é necessário . ",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno!",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *   )
     */
    public function index()
    {
        try {
            $usuarios = $this->repository->getUsuarios();

            return response()->json([
                'status' => true,
                'usuarios' => $usuarios
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar usuarios!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/usuarios",
     *   tags={"Usuarios"},
     *   summary="Cadastrar Novo Usuario",
     *   operationId="novo_usuario",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","email", "password", "nivel"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   ),
     *    @OA\Response(
     *      response=417,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'password' => 'required|string',
                'email' => 'required|string|email|unique:Usuarios,email'
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel cadastrar usuário!',
                    'error' => $validator->errors()
                ], 417);
            }

            $usuario = $this->repository->store($request->all());

            return Response([
                'status' => true,
                'message' => 'Usuário cadastrado com sucesso!',
                'usuario' => $usuario
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel realizar cadastro!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Put(
     ** path="/api/v1/usuarios/{id}",
     *   tags={"Usuarios"},
     *   summary="Alterar cadastro do usuario",
     *   operationId="editar_usuario",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","email", "password", "nivel"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   ),
     *    @OA\Response(
     *      response=417,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'email' => 'required|string|email'
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel alterar cadastro!',
                    'error' => $validator->errors()
                ], 417);
            }

            $this->repository->update($id, $request->all());

            return Response([
                'status' => true,
                'message' => 'Cadastro alterado com sucesso!'
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel alterar cadastro!',
                'error' => $e
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     ** path="/api/v1/usuarios/{id}",
     *   tags={"Usuarios"},
     *   summary="Excluir cadastro de um usuario",
     *   operationId="excluir_usuario",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   )
     *)
     **/
    public function destroy($id)
    {
        try {
            $deletarUsuarios = $this->repository->destroy($id);

            if (!$deletarUsuarios) {
                return Response()->json([
                    'status' => false,
                    'message' => 'Não foi possivel excluir usuário!'
                ], 417);
            }

            return Response()->json([
                'status' => true,
                'message' => 'Usuário excluido com sucesso!'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel excluir usuário!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/usuarios/login",
     *   tags={"Usuarios"},
     *   summary="Efetuar login ",
     *   operationId="login_usuario",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário . "
     *   ),
     *    @OA\Response(
     *      response=417,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return  Response([
                'status' => true,
                'message' => 'Não foi possivel fazer login!',
                'error' => $validator->errors()
            ], 417);
        }

        $token = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Email/ou Senha inválido(s)!',
            ], 401);
        }

        $usuario = Auth::user();

        return response()->json([
            'status' => true,
            'token' => $token,
            'usuario' => $usuario,
        ]);
    }
}
