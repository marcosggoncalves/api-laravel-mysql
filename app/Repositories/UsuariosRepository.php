<?php

namespace App\Repositories;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\IUsuariosInterface;

class UsuariosRepository implements IUsuariosInterface
{
    public function hashPassword($password)
    {
        return Hash::make($password);
    }

    public function getUsuarios()
    {
        return Usuario::orderBy('nome')->paginate(15);
    }

    public function store(array $request)
    {
        return Usuario::create([
            'nome' => $request['nome'],
            'email' => $request['email'],
            'password' => $this->HashPassword($request['password'])
        ]);
    }

    public function update(int $id, array $request)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->nome = $request['nome'];
        $usuario->email = $request['email'];

        if (isset($request['password'])) {
            $usuario->password = $this->HashPassword($request['password']);
        }

        return $usuario->save();
    }

    public function destroy(int $id)
    {
        return Usuario::destroy($id);
    }
}
