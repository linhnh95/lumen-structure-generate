<?php

namespace Linhnh95\LaravelLumenGenerate\CreateFiles;

use Illuminate\Console\GeneratorCommand;

class CommandCreateInterface extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lumen-generate:interface {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Interface Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'lumen-generate interface';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . 'interface.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . DIRECTORY_SEPARATOR . 'Abstraction';
    }

    /**
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $nameBase = $this->qualifyClass($this->getNameInput());
        $nameInput = $this->getNameInput() . 'Interface';
        $name = $this->qualifyClass($nameInput);
        $path = $this->getPath($name);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($nameInput)) {
            $this->error($this->type . ' already exists!');
            return false;
        }
        $this->makeDirectory($path);
        if (method_exists($this, 'sortImports')) {
            $this->files->put($path, $this->sortImports($this->buildClass($nameBase)));
        } else {
            $this->files->put($path, $this->buildClass($nameBase));
        }
        $this->info($this->type . ' created successfully.');
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string $stub
     * @param  string $name
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $stub = parent::replaceClass($stub, $name);
        $stub = $this->replaceVariable($stub);
        return $stub;
    }

    /**
     * @param $stub
     *
     * @return mixed
     */
    protected function replaceVariable($stub)
    {
        $variable = $this->qualifyClass($this->getNameInput());
        $variable = $this->qualifyClass($this->getNameInput());
        $variable = str_replace($this->getNamespace($variable).'\\', '', $variable);
        $variable = lcfirst($variable);
        return str_replace('{{variable}}', $variable, $stub);
    }
}