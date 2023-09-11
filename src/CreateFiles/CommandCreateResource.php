<?php

namespace Linhnh95\LaravelLumenGenerate\CreateFiles;

use Illuminate\Console\GeneratorCommand;
use Linhnh95\LaravelLumenGenerate\CommandHelpers;

class CommandCreateResource extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lumen-generate:resource-item {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Resource Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'lumen-generate resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . 'resource.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Resources';
    }

    /**
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $director = CommandHelpers::getDirectorAndFilename($this->getNameInput());
        $nameBase = $this->qualifyClass($this->getNameInput());
        $nameInput = $this->getNameInput() . 'Resource';
        $namePath = $this->getDefaultNamespace($this->rootNamespace()) . DIRECTORY_SEPARATOR . $director['folder'] . DIRECTORY_SEPARATOR . $director['name'] . DIRECTORY_SEPARATOR . $director['name'] . 'Resource';
        $path = $this->getPath($namePath);
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

    protected function replaceClass($stub, $name)
    {
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
        $class = str_replace($namespace . '\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }

    protected function getNamespace($name): string
    {
        $director = CommandHelpers::getDirectorAndFilename($this->getNameInput());
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\') . '\\' . $director['name'];
    }
}