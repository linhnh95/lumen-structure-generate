<?php

namespace Linhnh95\LaravelLumenGenerate\CreateFiles;

use Illuminate\Console\GeneratorCommand;

class CommandCreateRequest extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lumen-generate:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Request Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'lumen-generate request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . 'request.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Requests';
    }

    /**
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $director = $this->getDirectorAndFilename($this->getNameInput());

        $requests = [
            'Create' . $director['name'] . 'Request',
            'Update' . $director['name'] . 'Request',
            'List' . $director['name'] . 'Request',
            'Find' . $director['name'] . 'Request',
            'Delete' . $director['name'] . 'Request'
        ];

        foreach ($requests as $request) {
            $name = $this->qualifyClass($request);
            $nameInput = $director['folder'] . DIRECTORY_SEPARATOR . $name;
            $nameBase = $this->qualifyClass($nameInput);
            $path = $this->getPath($name);
            if ((!$this->hasOption('force') ||
                    !$this->option('force')) &&
                $this->alreadyExists($request)) {
                $this->error($this->type . ' already exists!');
                return false;
            }
            $this->makeDirectory($path);
            if (method_exists($this, 'sortImports')) {
                $this->files->put($path, $this->sortImports($this->buildClass($nameBase)));
            } else {
                $this->files->put($path, $this->buildClass($nameBase));
            }

        }
        $this->info($this->type . ' created successfully.');
    }

    /**
     * @param string $name
     * @return array
     */
    private function getDirectorAndFilename(string $name = ''): array
    {
        $explode = explode('/', $name);
        $name = array_pop($explode);
        $directory = implode(DIRECTORY_SEPARATOR, $explode);
        return [
            'name' => $name,
            'folder' => $directory
        ];
    }
}