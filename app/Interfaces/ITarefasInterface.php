<?php

namespace App\Interfaces;

interface ITarefasInterface
{
    public function getTarefas();

    public function getTarefa(int $id);

    public function store(array $request);

    public function update(int $id, array $request);
    
    public function destroy(int $id);
}
