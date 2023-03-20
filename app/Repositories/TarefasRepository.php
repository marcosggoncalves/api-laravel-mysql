<?php

namespace App\Repositories;

use App\Interfaces\ITarefasInterface;
use App\Models\Tarefa;

class TarefasRepository implements ITarefasInterface
{
    public function getTarefas()
    {
        return Tarefa::orderBy('id', 'desc')->paginate(15);
    }

    public function getTarefa($id)
    {
        return  Tarefa::findOrFail($id);
    }

    public function store(array $request)
    {
        return Tarefa::create([
            'titulo' => $request['titulo'],
            'descricao' => $request['descricao'],
            'data_conclusao' => $request['data_conclusao']
        ]);
    }

    public function update(int $id, array $request)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->titulo = $request['titulo'];
        $tarefa->descricao = $request['descricao'];
        $tarefa->data_conclusao = $request['data_conclusao'];
        
        return $tarefa->save();
    }

    public function destroy(int $id)
    {
        return Tarefa::destroy($id);
    }
}
