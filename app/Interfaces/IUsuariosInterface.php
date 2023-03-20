<?php

namespace App\Interfaces;

interface IUsuariosInterface
{
    public function getUsuarios();

    public function store(array $request);

    public function update(int $id, array $request);
    
    public function destroy(int $id);
}
