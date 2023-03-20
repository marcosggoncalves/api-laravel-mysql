<?php

namespace App\Providers;

use App\Interfaces\ITarefasInterface;
use App\Interfaces\IUsuariosInterface;
use App\Repositories\TarefasRepository;
use App\Repositories\UsuariosRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IUsuariosInterface::class, UsuariosRepository::class);
        $this->app->bind(ITarefasInterface::class, TarefasRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
