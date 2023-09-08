<?php

namespace Linhnh95\LaravelLumenGenerate;

use Illuminate\Support\ServiceProvider;
use Linhnh95\LaravelLumenGenerate\CreateFiles\CommandCreateCollection;
use Linhnh95\LaravelLumenGenerate\CreateFiles\CommandCreateController;
use Linhnh95\LaravelLumenGenerate\CreateFiles\CommandCreateInterface;
use Linhnh95\LaravelLumenGenerate\CreateFiles\CommandCreateRepository;
use Linhnh95\LaravelLumenGenerate\CreateFiles\CommandCreateRequest;
use Linhnh95\LaravelLumenGenerate\CreateFiles\CommandCreateResource;

class LaravelLumenGenerateProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerHelpers();

        if ($this->app->runningInConsole()) {
            $this->commands([
                CommandCreateFiles::class,
                CommandCreateCollection::class,
                CommandCreateController::class,
                CommandCreateInterface::class,
                CommandCreateRepository::class,
                CommandCreateRequest::class,
                CommandCreateResource::class
            ]);
        }
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register custom helpers
     */
    protected function registerHelpers()
    {

    }
}