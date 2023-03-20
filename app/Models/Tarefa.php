<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descricao', 'data_conclusao'];

    protected $hidden = ['updated_at'];
}
