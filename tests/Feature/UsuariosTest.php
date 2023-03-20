<?php

namespace Tests\Feature;

use Tests\TestCase;

class UsuarioTest extends TestCase
{
    private $route = 'api/v1/usuarios';

    private function rand() {
        return  rand(2300, 3300);
    }

    private function login($auth)
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post("{$this->route}/login", $auth);

        return $response;
    }

    public function testTestarAutenticacao()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get("{$this->route}");

        $response->assertStatus(401);
    }

    public function testEfetuarLogin()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $this->login($auth)->assertStatus(200);
    }

    public function testEfetuarLoginCamposVazios()
    {
        $auth = [
            'email' => '',
            'password' => ''
        ];

        $this->login($auth)->assertStatus(417);
    }

    public function testEfetuarLoginEmailSenhaIncorreta()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.1',
            'password' => '1231'
        ];

        $this->login($auth)->assertStatus(401);
    }

    public function testListar()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->get("{$this->route}");

        $response->assertStatus(200);
    }

    public function testNovoCadastro()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $email = "teste.novo{$this->rand()}@gmail.com";

        $cadastro = [
            'nome' => 'Marcos',
            'email' => $email,
            'password' => '1255',
            'nivel' => 1
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $response->assertStatus(200);
    }

    public function testNovoCadastroCamposVazios()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastroCamposVazios = [
            'nome' => null,
            'email' => null,
            'password' => null,
            'nivel' => null
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastroCamposVazios);

        $response->assertStatus(417);
    }

    public function testNovoCadastroEmailJaUtilizado()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastroCamposVazios = [
            'nome' => 'marcoslopes',
            'email' => "marcoslopesg7@gmail.com",
            'password' => '1234',
            'nivel' => 1
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastroCamposVazios);

        $response->assertJson(
            [
                "status" => true,
                "message" => "Não foi possivel cadastrar usuário!",
                "error" => [
                    "email" => [
                        "O campo email já está sendo utilizado."
                    ]
                ]
            ]
        );
    }

    public function testAlterarCadastro()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $email = "teste.alterar{$this->rand()}@gmail.com";

        /// Criar novo cadastro 
        $cadastro = [
            'nome' => 'Marcos',
            'email' => $email,
            'password' => '1255',
            'nivel' => 1
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Editar cadastro criado
        $cadastroEdit = [
            'nome' => 'Marcos Lopes',
            'email' => $novoCadastro['usuario']['email'],
            'password' => 'Lopes@711',
            'nivel' => 2
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->put("{$this->route}/{$novoCadastro['usuario']['id']}", $cadastroEdit);

        $response->assertStatus(200);
    }

    public function testExcluirCadastro()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $email = "teste.alterar{$this->rand()}@gmail.com";

        /// Criar novo cadastro 
        $cadastro = [
            'nome' => 'Marcos',
            'email' => $email,
            'password' => '1255'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Excluir cadastro criado
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->delete("{$this->route}/{$novoCadastro['usuario']['id']}");

        $response->assertJson([
            "status" => true,
            "message" => "Usuário excluido com sucesso!"
        ]);
    }
}
