<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tarefas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =  date('Y-m-d H:i:s');

        DB::table('tarefas')->insert([
            [
                'titulo' => 'Mercado',
                'descricao' => 'Fazer compras de itens necessários.',
                'data_conclusao' => $data,
                'created_at' =>  $data,
                'updated_at' =>  $data
            ],
            [
                'titulo' => 'Revisão do carro',
                'descricao' => 'Levar carro para fazer revisão.',
                'data_conclusao' => null,
                'created_at' =>  $data,
                'updated_at' =>  $data
            ]
        ]);
    }
}
