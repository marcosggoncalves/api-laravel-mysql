<?php

namespace App\Http\Controllers;

use App\Interfaces\ITarefasInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class TarefasController extends Controller
{
    protected $repository;

    public function __construct(ITarefasInterface $repository)
    {
        $this->middleware(['auth']);

        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/tarefas",
     *      operationId="listar_tarefas",
     *      tags={"Tarefas"},
     *      summary="Listar tarefas",
     *      description="Retornar todas as tarefas cadastradas",
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
            $tarefas = $this->repository->getTarefas();

            return response()->json([
                'status' => true,
                'tarefas' => $tarefas
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar tarefas!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/tarefas/{id}",
     *      operationId="get_tarefa",
     *      tags={"Tarefas"},
     *      summary="Detalhar tarefa",
     *      description="Retornar informação de uma tarefa",
     *      security={{"bearerAuth":{}}}, 
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
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
    public function show($id)
    {
        try {
            $tarefa = $this->repository->getTarefa($id);

            return response()->json([
                'status' => true,
                'tarefa' => $tarefa
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel detalhar tarefa especifica!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/tarefas",
     *   tags={"Tarefas"},
     *   summary="Cadastrar nova tarefa",
     *   operationId="nova_tarefa",
     *   security={{"bearerAuth":{}}}, 
     * @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"titulo", "descricao", "data_conclusao"},
     *       @OA\Property(property="titulo", type="titulo", format="text", example="Revisão do carro"),
     *       @OA\Property(property="descricao", type="descricao", format="text", example="Levar o carro na concessionária para realizar a revisão."),
     *       @OA\Property(property="data_conclusao", type="data_conclusao", format="date", example="2023-03-19"),
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
                'titulo' => 'required|string|min:5',
                'descricao' => 'required|string|min:10'
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel cadastrar tarefa!',
                    'error' => $validator->errors()
                ], 417);
            }

            $tarefa = $this->repository->store($request->all());

            return Response([
                'status' => true,
                'message' => 'Tarefa cadastrada com sucesso!',
                'tarefa' => $tarefa
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
     ** path="/api/v1/tarefas/{id}",
     *   tags={"Tarefas"},
     *   summary="Alterar detalhe da tarefa",
     *   operationId="editar_tarefa",
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
     *       required={"titulo", "descricao", "data_conclusao"},
     *       @OA\Property(property="titulo", type="titulo", format="text", example="Revisão do carro"),
     *       @OA\Property(property="descricao", type="descricao", format="text", example="Levar o carro na concessionária para realizar a revisão."),
     *       @OA\Property(property="data_conclusao", type="data_conclusao", format="date", example="2023-03-19"),
     *    ),
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
                'titulo' => 'required|string|min:5',
                'descricao' => 'required|string|min:10'
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
                'message' => 'Tarefa alterada com sucesso!'
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel alterar tarefa!',
                'error' => $e
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     ** path="/api/v1/tarefas/{id}",
     *   tags={"Tarefas"},
     *   summary="Excluir tarefa especifica",
     *   operationId="excluir_tarefa",
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
            $deletarTarefa = $this->repository->destroy($id);

            if (!$deletarTarefa) {
                return Response()->json([
                    'status' => false,
                    'message' => 'Não foi possivel excluir tarefa!'
                ], 417);
            }

            return Response()->json([
                'status' => true,
                'message' => 'Tarefa excluida com sucesso!'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel excluir tarefa!',
                'error' => $e
            ], 500);
        }
    }
}
