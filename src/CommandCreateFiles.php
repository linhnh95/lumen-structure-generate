<?php

namespace Linhnh95\LaravelLumenGenerate;

use Illuminate\Console\Command;

class CommandCreateFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lumen-generate:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Files Of Linh Nguyen Hong';

    /**
     * @var CommandHelpers
     */
    private $commandHelper;

    /**
     * CreateFileCommand constructor.
     *
     * @param CommandHelpers $commandHelper
     */
    public function __construct(CommandHelpers $commandHelper)
    {
        $this->commandHelper = $commandHelper;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $director = $this->getDirectorAndFilename($this->argument('name'));
        $this->callSilent('lumen-generate:interface', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:repository', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:controller', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:resource-collection', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:resource-item', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:request', ['name' => $this->argument('name')]);
        $this->createProviders($director['name']);
        $this->info('Create files success');
    }

    /**
     * @param $name
     */
    private function createProviders($name)
    {
        $stringAddNew = '$this->app->bind(' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Abstraction' . DIRECTORY_SEPARATOR . $name . 'Interface::class, ' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . $name . 'Repository::class);' . "\n" . '        ';
        $file = $this->commandHelper->getAppPath() . '/Providers/RepositoryProvider.php';
        $contentFile = file_get_contents($file);
        $changeContent = substr($contentFile, strripos($contentFile, '/**BEGIN CONFIG**/'));
        $changeContent = substr($changeContent, 0, strripos($changeContent, '/**END CONFIG**/'));
        $stringReplace = $changeContent . $stringAddNew;
        $afterString = str_replace($changeContent, $stringReplace, $contentFile);
        file_put_contents($file, '');
        file_put_contents($file, $afterString);
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