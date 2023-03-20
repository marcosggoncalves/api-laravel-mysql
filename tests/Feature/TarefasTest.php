<?php

namespace Tests\Feature;

use Tests\TestCase;

class TarefasTest extends TestCase
{
    private $route = 'api/v1/tarefas';

    private $routeAuth = 'api/v1/usuarios';

    private function login($auth)
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post("{$this->routeAuth}/login", $auth);

        return $response;
    }

    public function testTestarAutenticacao()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get("{$this->route}");

        $response->assertStatus(401);
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

    public function testNovaTarefa()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastro = [
            "titulo" => "Entregar teste",
            "descricao" => "Entregar teste prático de backend",
            "data_conclusao" => "2023-03-20"
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $response->assertStatus(200);
    }

    public function testNovaTarefaCamposVazios()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastroCamposVazios = [
            "titulo" => null,
            "descricao" => null
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastroCamposVazios);

        $response->assertStatus(417);
    }

    public function testAlterarTarefa()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        /// Criar cadastro para editar
        $cadastro = [
            "titulo" => "Entregar teste",
            "descricao" => "Entregar teste prático de backend",
            "data_conclusao" => null
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Editar Cadastro 
        $cadastroEdit = $novoCadastro['tarefa'];
        $cadastroEdit['titulo'] = "Entregar teste(editado)";
        $cadastroEdit['data_conclusao'] = date('Y-m-d');


        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->put("{$this->route}/{$novoCadastro['tarefa']['id']}", $cadastroEdit);

        $response->assertStatus(200);
    }

    public function testExcluirTarefa()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        /// Criar cadastro para editar
        $cadastro = [
            "titulo" => "Local um carro",
            "descricao" => "Preciso locar um carro, pois o meu irá para manutenção.",
            "data_conclusao" => "2023-02-20"
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Excluir Cadastro criado
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->delete("{$this->route}/{$novoCadastro['tarefa']['id']}");


        $response->assertJson([
            "status" => true,
            "message" => "Tarefa excluida com sucesso!"
        ]);
    }
}
