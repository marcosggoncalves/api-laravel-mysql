<?php

namespace Tests\Unit;

use App\Interfaces\IUsuariosInterface;
use App\Repositories\UsuariosRepository;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsuariosTest extends TestCase
{
    public static function repository(): IUsuariosInterface
    {
        return new UsuariosRepository();
    }

    private function gerarCadastro()
    {
        $random = rand(1200, 2200);

        $cadastro = [
            'nome' => 'Teste',
            'password' => Hash::make('1234'),
            'email' => "unitario.usuario{$random}@gmail.com"
        ];

        return $cadastro;
    }

    public function testListar()
    {
        $novoUsuario = $this->gerarCadastro();
        /// Salvar
        self::repository()->store($novoUsuario);
        /// Listar
        $usuario = self::repository()->getUsuarios();

        $this->assertTrue($usuario[0]->exists);
    }

    public function testCriar()
    {
        $novoUsuario = $this->gerarCadastro();
        /// Salvar
        $salvarUsuario = self::repository()->store($novoUsuario);

        $this->assertEquals($salvarUsuario->nome, $novoUsuario['nome']);
    }

    public function testAlterar()
    {
        $novoUsuario = $this->gerarCadastro();
        /// Salvar   
        $salvarUsuario = self::repository()->store($novoUsuario);
        /// Editar 
        $editarUsuario = self::repository()->update($salvarUsuario->id, $novoUsuario);

        $this->assertEquals($editarUsuario, true);
    }

    public function testDeletar()
    {
        $novoUsuario = $this->gerarCadastro();
        /// Salvar   
        $salvarUsuario = self::repository()->store($novoUsuario);
        /// Excluir 
        $deletarUsuario = self::repository()->destroy($salvarUsuario->id);

        $this->assertEquals($deletarUsuario, true);
    }
}
