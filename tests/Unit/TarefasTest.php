<?php

namespace Tests\Unit;

use App\Interfaces\ITarefasInterface;
use App\Repositories\TarefasRepository;
use Tests\TestCase;

class TarefasTest extends TestCase
{
    public static function repository(): ITarefasInterface
    {
        return new TarefasRepository();
    }

    private function gerarTarefa()
    {
        $random = rand(0, 1000);

        $tarefa = [
            'titulo' => "Tarefa-{$random}",
            'descricao' => "Tarefa teste unitário - {$random}",
            'data_conclusao' => date('Y-m-d')
        ];

        return $tarefa;
    }

    public function testDetalhar()
    {
        $novaTarefa = $this->gerarTarefa();
        /// Salvar   
        $salvarTarefa = self::repository()->store($novaTarefa);
        /// Buscar
        $tarefa = self::repository()->getTarefa($salvarTarefa->id);

        $this->assertEquals($tarefa->titulo, $novaTarefa['titulo']);
    }

    public function testListar()
    {
        $novaTarefa = $this->gerarTarefa();
        /// Salvar 
        self::repository()->store($novaTarefa);
        /// Listar 
        $tarefas = self::repository()->getTarefas();
        
        $this->assertNotEmpty($tarefas);
    }

    public function testCriar()
    {
        $novaTarefa = $this->gerarTarefa();
        /// Salvar 
        $salvarTarefa = self::repository()->store($novaTarefa);

        $this->assertEquals($salvarTarefa->titulo, $novaTarefa['titulo']);
    }

    public function testAlterar()
    {
        $novaTarefa = $this->gerarTarefa();
        /// Salvar   
        $salvarTarefa = self::repository()->store($novaTarefa);
        /// Editar
        $editarTarefa = self::repository()->update($salvarTarefa->id, $novaTarefa);

        $this->assertEquals($editarTarefa, true);
    }

    public function testDeletar()
    {
        $novaTarefa = $this->gerarTarefa();
        /// Salvar   
        $salvarTarefa = self::repository()->store($novaTarefa);
        /// Excluir
        $deletarTarefa = self::repository()->destroy($salvarTarefa->id);

        $this->assertEquals($deletarTarefa, true);
    }
}
